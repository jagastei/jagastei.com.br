<?php

declare(strict_types=1);

use App\Http\Controllers\Webhooks\WhatsappController;
use Illuminate\Support\Facades\Route;

Route::post('/webhooks/whatsapp', [WhatsappController::class, 'store']);
