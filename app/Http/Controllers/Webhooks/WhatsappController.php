<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Jobs\HandleWhatsappMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsappController extends Controller
{
    public function store(Request $request)
    {
        return response()->noContent(200);

        Log::channel('whatsapp')->info('Webhook recebido', $request->all());

        $input = $request->all();

        HandleWhatsappMessage::dispatch($input);

        return response()->noContent(200);
    }
}
