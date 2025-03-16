<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupportRequest;
use App\Mail\Support;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SupportController extends Controller
{
    public function store(SupportRequest $request)
    {
        $input = $request->validated();

        Log::channel('support')->info('*** SUPPORT ***');
        Log::channel('support')->info($input);
        Log::channel('support')->info('*** SUPPORT ***');

        $user = $request->user();

        Mail::to(config('mail.to.address'))->send(new Support(
            name: $user->name,
            email: $user->email,
            message: $input['message'],
        ));

        return back();
    }
}
