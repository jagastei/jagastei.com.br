<?php

declare(strict_types=1);

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Laravel\Cashier\Events\WebhookReceived;

final class StripeEventListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(WebhookReceived $event): void
    {
        Log::info('Stripe webhook received', $event->payload);
    }
}
