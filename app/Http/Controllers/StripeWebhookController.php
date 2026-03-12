<?php

namespace App\Http\Controllers;

use App\Models\ApiSubscription;
use App\Models\BillingInvoice;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;
use Stripe\Webhook;
use UnexpectedValueException;

class StripeWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $secret = config('services.stripe.secret');
        $webhookSecret = config('services.stripe.webhook_secret');

        if (!is_string($secret) || trim($secret) === '' || !is_string($webhookSecret) || trim($webhookSecret) === '') {
            return response()->json(['success' => false, 'message' => 'Stripe webhook is not configured'], 500);
        }

        Stripe::setApiKey($secret);

        try {
            $event = Webhook::constructEvent(
                $request->getContent(),
                (string) $request->header('Stripe-Signature'),
                $webhookSecret
            );
        } catch (UnexpectedValueException|SignatureVerificationException $e) {
            Log::warning('Invalid Stripe webhook signature', ['error' => $e->getMessage()]);
            return response()->json(['success' => false], 400);
        }

        $type = $event->type;
        $object = $event->data->object;

        match ($type) {
            'checkout.session.completed' => $this->handleCheckoutCompleted($object),
            'customer.subscription.created', 'customer.subscription.updated' => $this->handleSubscriptionUpdated($object),
            'customer.subscription.deleted' => $this->handleSubscriptionDeleted($object),
            'invoice.payment_succeeded', 'invoice.payment_failed' => $this->handleInvoiceEvent($object),
            default => null,
        };

        return response()->json(['success' => true]);
    }

    private function handleCheckoutCompleted(object $session): void
    {
        $userId = (int) ($session->metadata->user_id ?? 0);
        $plan = (string) ($session->metadata->plan ?? '');

        if ($userId < 1 || $plan === '') {
            return;
        }

        $user = User::find($userId);
        if (!$user) {
            return;
        }

        $user->update([
            'api_plan' => $plan,
            'stripe_customer_id' => $session->customer ?? $user->stripe_customer_id,
        ]);

        ApiSubscription::updateOrCreate(
            ['user_id' => $user->id],
            [
                'plan' => $plan,
                'status' => 'active',
                'stripe_subscription_id' => $session->subscription ?? null,
                'stripe_customer_id' => $session->customer ?? null,
            ]
        );
    }

    private function handleSubscriptionUpdated(object $subscription): void
    {
        $user = User::where('stripe_customer_id', $subscription->customer)->first();
        if (!$user) {
            return;
        }

        $priceId = $subscription->items->data[0]->price->id ?? null;
        $plan = $this->planFromPriceId($priceId) ?? $user->api_plan;

        $user->update(['api_plan' => $plan]);

        ApiSubscription::updateOrCreate(
            ['user_id' => $user->id],
            [
                'plan' => $plan,
                'status' => (string) $subscription->status,
                'stripe_subscription_id' => (string) $subscription->id,
                'stripe_customer_id' => (string) $subscription->customer,
                'current_period_end' => isset($subscription->current_period_end) ? now()->createFromTimestamp((int) $subscription->current_period_end) : null,
                'canceled_at' => isset($subscription->canceled_at) && $subscription->canceled_at ? now()->createFromTimestamp((int) $subscription->canceled_at) : null,
            ]
        );
    }

    private function handleSubscriptionDeleted(object $subscription): void
    {
        $user = User::where('stripe_customer_id', $subscription->customer)->first();
        if (!$user) {
            return;
        }

        $user->update(['api_plan' => config('api_plans.default', 'free')]);

        ApiSubscription::updateOrCreate(
            ['user_id' => $user->id],
            [
                'plan' => config('api_plans.default', 'free'),
                'status' => 'canceled',
                'stripe_subscription_id' => (string) $subscription->id,
                'stripe_customer_id' => (string) $subscription->customer,
                'canceled_at' => now(),
            ]
        );
    }

    private function handleInvoiceEvent(object $invoice): void
    {
        $user = User::where('stripe_customer_id', $invoice->customer)->first();
        if (!$user) {
            return;
        }

        $line = $invoice->lines->data[0] ?? null;
        $periodStart = $line?->period?->start ?? null;
        $periodEnd = $line?->period?->end ?? null;

        BillingInvoice::updateOrCreate(
            ['stripe_invoice_id' => (string) $invoice->id],
            [
                'user_id' => $user->id,
                'stripe_subscription_id' => $invoice->subscription ?? null,
                'status' => $invoice->status ?? null,
                'currency' => $invoice->currency ?? null,
                'amount_due' => (int) ($invoice->amount_due ?? 0),
                'amount_paid' => (int) ($invoice->amount_paid ?? 0),
                'period_start' => $periodStart ? now()->createFromTimestamp((int) $periodStart) : null,
                'period_end' => $periodEnd ? now()->createFromTimestamp((int) $periodEnd) : null,
                'hosted_invoice_url' => $invoice->hosted_invoice_url ?? null,
                'invoice_pdf' => $invoice->invoice_pdf ?? null,
                'paid_at' => !empty($invoice->status_transitions->paid_at)
                    ? now()->createFromTimestamp((int) $invoice->status_transitions->paid_at)
                    : null,
            ]
        );
    }

    private function planFromPriceId(?string $priceId): ?string
    {
        if (!$priceId) {
            return null;
        }

        foreach ((array) config('api_plans.plans', []) as $key => $plan) {
            if (($plan['stripe_price_id'] ?? null) === $priceId) {
                return $key;
            }
        }

        return null;
    }
}