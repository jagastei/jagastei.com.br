<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Cashier\Exceptions\IncompletePayment;

class StripeController extends Controller
{
    public function billing(Request $request)
    {
        if (! $request->user()->hasStripeId()) {
            abort(403, 'User does not have a Stripe customer ID.');
        }

        return $request->user()->redirectToBillingPortal(route('dashboard'));
    }

    public function checkout(Request $request)
    {
        try {
            // $request->user()
            //     ->forceFill([
            //         'trial_ends_at' => now()->addDays(5),
            //     ])->save();

            $subscription = $request->user()
                ->newSubscription('prod_Qqo7VkRXdZR60n', 'price_1Pz6VRChkwYDN5df8JO1WARg')
                // ->create($paymentMethod)
                ->trialDays(5)
                ->allowPromotionCodes()
                ->checkout([
                    'success_url' => route('subscription.checkout-success'),
                    'cancel_url' => route('subscription.checkout-cancel'),
                ]);

            return $subscription;
        } catch (IncompletePayment $exception) {
            return redirect()->route(
                'cashier.payment',
                [$exception->payment->id, 'redirect' => route('home')]
            );
        }
    }

    public function checkoutSuccess(Request $request)
    {
        return redirect()->route('dashboard');
    }

    public function checkoutCancel(Request $request)
    {
        return redirect()->route('dashboard');
    }
}
