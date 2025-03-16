<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedbackRequest;
use App\Mail\Feedback;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class FeedbackController extends Controller
{
    public function store(FeedbackRequest $request)
    {
        $input = $request->validated();

        Log::channel('feedback')->info('*** FEEDBACK ***');
        Log::channel('feedback')->info($input);
        Log::channel('feedback')->info('*** FEEDBACK ***');

        $user = $request->user();

        Mail::to(config('mail.to.address'))->send(new Feedback(
            name: $user->name,
            email: $user->email,
            message: $input['message'],
            rating: $input['rating'],
        ));

        return back();
    }
}
