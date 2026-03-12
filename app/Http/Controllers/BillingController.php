<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Stripe\BillingPortal\Session as BillingPortalSession;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Stripe;

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

        if (!$user->stripe_customer_id) {
            $customer = Customer::create([
                'email' => $user->email,
                'name' => $user->name,
                'metadata' => ['user_id' => (string) $user->id],
            ]);

            $user->update(['stripe_customer_id' => $customer->id]);
        }

        $session = Session::create([
            'mode' => 'subscription',
            'customer' => $user->stripe_customer_id,
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

        if (!$user->stripe_customer_id) {
            return back()->with('billing_error', 'No Stripe billing profile found for this account.');
        }

        $secret = config('services.stripe.secret');
        if (!is_string($secret) || trim($secret) === '') {
            return back()->with('billing_error', 'Stripe billing is not configured. Please contact support.');
        }

        Stripe::setApiKey($secret);

        $session = BillingPortalSession::create([
            'customer' => $user->stripe_customer_id,
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
}