<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Cache;

class VerifyPhone extends Notification
{
    public function via(object $notifiable): string
    {
        return WhatsappChannel::class;
    }

    public function toWhatsapp(object $notifiable): string
    {
        return 'Olá, seu código de verificação é: ' . $this->verificationCode($notifiable);
    }

    protected function verificationCode(object $notifiable): string
    {
        $code = rand(100000, 999999);

        Cache::store('redis')->put(
            'verification_code_' . $notifiable->getKey(),
            sha1($code),
            60
        );

        return $code;
    }
}
