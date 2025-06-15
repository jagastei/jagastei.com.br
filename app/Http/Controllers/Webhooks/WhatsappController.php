<?php

declare(strict_types=1);

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Jobs\HandleWhatsappMessage;
use Illuminate\Http\Request;

final class WhatsappController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->all();

        HandleWhatsappMessage::dispatch($input);

        return response()->noContent(200);
    }
}
