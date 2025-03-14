<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FeedbackRequest;
use Illuminate\Support\Facades\Log;

class FeedbackController extends Controller
{
    public function store(FeedbackRequest $request)
    {
        $input = $request->validated();

        Log::channel('feedback')->info('*** FEEDBACK ***');
        Log::channel('feedback')->info($input);
        Log::channel('feedback')->info('*** FEEDBACK ***');

        return back();
    }
}
