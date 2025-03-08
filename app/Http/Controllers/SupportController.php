<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupportRequest;
use Illuminate\Support\Facades\Log;

class SupportController extends Controller
{
    public function store(SupportRequest $request)
    {
        $input = $request->validated();

        Log::info('*** SUPPORT ***');
        Log::info($input);
        Log::info('*** SUPPORT ***');

        return back();
    }
}
