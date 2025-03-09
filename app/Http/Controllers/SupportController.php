<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupportRequest;
use Illuminate\Support\Facades\Log;

class SupportController extends Controller
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
