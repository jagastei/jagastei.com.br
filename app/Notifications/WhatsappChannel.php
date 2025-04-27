<?php

namespace App\Notifications;

use Exception;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappChannel
{
    public function send(object $notifiable, Notification $notification)
    {
        /** @var \App\Notifications\VerifyPhone $notification */
        $message = $notification->toWhatsapp($notifiable);

        /** @var \App\Models\User $notifiable */
        if (! $phone = $notifiable->routeNotificationFor('whatsapp', $notification)) {
            return;
        }

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'apikey' => config('services.whatsapp.api_key'),
            ])->post(config('services.whatsapp.server_url').'/message/sendText/'.config('services.whatsapp.instance'), [
                'number' => $phone,
                'text' => $message,
            ]);

            Log::info($response->json());
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
