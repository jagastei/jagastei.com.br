<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SupportRequest;
use Illuminate\Support\Facades\Log;

final class SupportController extends Controller
{
    public function store(SupportRequest $request)
    {
        $input = $request->validated();

        Log::channel('support')->info('*** SUPPORT ***');
        Log::channel('support')->info($input);
        Log::channel('support')->info('*** SUPPORT ***');

        return back();
    }
}
