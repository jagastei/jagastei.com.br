<?php

namespace App\Providers;

use App\Listeners\StripeEventListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\WebhookReceived;
use PostHog\PostHog;
use Illuminate\Support\Facades\Lang;
use Money\Money;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);

        // Cashier::keepPastDueSubscriptionsActive();
        // Cashier::keepIncompleteSubscriptionsActive();

        Event::listen(WebhookReceived::class, StripeEventListener::class);

        PostHog::init(config('services.posthog.key'), [
            'host' => config('services.posthog.host'),
        ]);

        // Lang::stringable(function (Money $money) {
        //     return $money->formatTo('en_GB');
        // });
    }
}
