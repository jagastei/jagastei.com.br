<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;
use PostHog\PostHog;

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

        setlocale(LC_TIME, config('app.locale'));
        Carbon::setLocale(config('app.locale'));

        PostHog::init(config('services.posthog.key'), [
            'host' => config('services.posthog.host'),
        ]);
    }
}
