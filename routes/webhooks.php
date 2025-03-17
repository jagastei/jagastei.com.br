<?php

use App\Http\Controllers\Webhooks\WhatsappController;
use Illuminate\Support\Facades\Route;

Route::post('/webhooks/whatsapp', [WhatsappController::class, 'store']);
