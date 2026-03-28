<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Stripe\Exception\InvalidRequestException;
use Stripe\BillingPortal\Session as BillingPortalSession;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Stripe;
use Throwable;

class BillingController extends Controller
{
    public function checkout(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'plan' => ['required', 'in:pro,business'],
        ]);

        $user = $request->user();
        $planKey = $validated['plan'];
        $planConfig = config("api_plans.plans.{$planKey}");
        $priceId = $planConfig['stripe_price_id'] ?? null;

        if (!is_string($priceId) || trim($priceId) === '') {
            return back()->with('billing_error', 'This paid plan is not configured yet. Please contact support.');
        }

        $secret = config('services.stripe.secret');
        if (!is_string($secret) || trim($secret) === '') {
            return back()->with('billing_error', 'Stripe billing is not configured. Please contact support.');
        }

        Stripe::setApiKey($secret);

        try {
            $customerId = $this->resolveStripeCustomerId($user);
        } catch (Throwable $e) {
            report($e);

            return back()->with('billing_error', 'Unable to initialize Stripe customer for this account. Please try again.');
        }

        $session = Session::create([
            'mode' => 'subscription',
            'customer' => $customerId,
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
            'allow_promotion_codes' => true,
            'success_url' => route('billing.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('profile.show'),
            'metadata' => [
                'user_id' => (string) $user->id,
                'plan' => $planKey,
            ],
        ]);

        return redirect()->away($session->url);
    }

    public function success(): RedirectResponse
    {
        return redirect()->route('profile.show')->with('success', 'Payment successful. Your plan will be updated shortly.');
    }

    public function portal(Request $request): RedirectResponse
    {
        $user = $request->user();

        $secret = config('services.stripe.secret');
        if (!is_string($secret) || trim($secret) === '') {
            return back()->with('billing_error', 'Stripe billing is not configured. Please contact support.');
        }

        Stripe::setApiKey($secret);

        try {
            $customerId = $this->resolveStripeCustomerId($user);
        } catch (Throwable $e) {
            report($e);

            return back()->with('billing_error', 'Unable to initialize Stripe customer for this account. Please try again.');
        }

        $session = BillingPortalSession::create([
            'customer' => $customerId,
            'return_url' => route('profile.show'),
        ]);

        return redirect()->away($session->url);
    }

    public function invoices(Request $request)
    {
        $invoices = $request->user()
            ->billingInvoices()
            ->latest('created_at')
            ->paginate(20);

        return view('profile.invoices', [
            'invoices' => $invoices,
            'user' => $request->user(),
        ]);
    }

    private function resolveStripeCustomerId(User $user): string
    {
        $currentId = trim((string) $user->stripe_customer_id);

        if ($currentId !== '') {
            try {
                Customer::retrieve($currentId, []);

                return $currentId;
            } catch (InvalidRequestException $e) {
                $isMissingCustomer = $e->getStripeCode() === 'resource_missing'
                    || str_contains($e->getMessage(), 'No such customer');

                if (!$isMissingCustomer) {
                    throw $e;
                }
            }
        }

        $customer = Customer::create([
            'email' => $user->email,
            'name' => $user->name,
            'metadata' => ['user_id' => (string) $user->id],
        ]);

        $user->update(['stripe_customer_id' => $customer->id]);

        return $customer->id;
    }
}