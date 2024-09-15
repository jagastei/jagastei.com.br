<?php

use App\Http\Controllers\ProfileController;
use App\Http\Middleware\Subscribed;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Cashier\Exceptions\IncompletePayment;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/table', function () {
    return Inertia::render('Table');
})->middleware(['auth', 'verified'])->name('table');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/billing', function (Request $request) {
        return $request->user()->redirectToBillingPortal(route('dashboard'));
    })->name('billing');

    Route::get('/subscription-checkout', function (Request $request) {

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
                    'success_url' => route('subscription-checkout-success'),
                    'cancel_url' => route('subscription-checkout-cancel'),
                ]);

            return $subscription;
        } catch (IncompletePayment $exception) {
            return redirect()->route(
                'cashier.payment',
                [$exception->payment->id, 'redirect' => route('home')]
            );
        }
    })->name('subscription-checkout');

    Route::get('/subscription-checkout-success', function (Request $request) {
        return 'Success';
    })->name('subscription-checkout-success');

    Route::get('/subscription-checkout-cancel', function (Request $request) {
        return 'Cancel';
    })->name('subscription-checkout-cancel');
});

require __DIR__ . '/auth.php';
