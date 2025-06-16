<?php

declare(strict_types=1);

namespace App\Providers;

use App\Listeners\StripeEventListener;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\Events\WebhookReceived;
use Money\Money;
use PostHog\PostHog;

final class AppServiceProvider extends ServiceProvider
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
        Model::shouldBeStrict();
        // Model::unguard();



        // Cashier::keepPastDueSubscriptionsActive();
        // Cashier::keepIncompleteSubscriptionsActive();

        Event::listen(WebhookReceived::class, StripeEventListener::class);

        PostHog::init(config('services.posthog.key'), [
            'host' => config('services.posthog.host'),
        ]);

        // Lang::stringable(function (Money $money) {
        //     return $money->formatTo('en_GB');
        // });

        // Vite::prefetch(concurrency: 3);
        Vite::useAggressivePrefetching();

        // URL::forceScheme('https');
    }
}
