<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Cashier\Exceptions\IncompletePayment;

class StripeController extends Controller
{
    public function billing(Request $request)
    {
        if (! $request->user()->hasStripeId()) {
            return redirect()->route('subscription.checkout');
        }

        return $request->user()->redirectToBillingPortal(route('dashboard'));
    }

    public function checkout(Request $request)
    {
        try {
            return $request->user()
                ->newSubscription('prod_RvrAGkmPd6GfFt', 'price_1R1zSbChkwYDN5dfkxKF3ec1')
                ->allowPromotionCodes()
                ->checkout([
                    'success_url' => route('subscription.checkout-success'),
                    'cancel_url' => route('subscription.checkout-cancel'),
                ]);
        } catch (IncompletePayment $exception) {
            return redirect()->route(
                'cashier.payment',
                [$exception->payment->id, 'redirect' => route('dashboard')]
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
