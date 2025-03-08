<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedbackRequest;
use Illuminate\Support\Facades\Log;

class FeedbackController extends Controller
{
    public function store(FeedbackRequest $request)
    {
        $input = $request->validated();

        Log::info('*** FEEDBACK ***');
        Log::info($input);
        Log::info('*** FEEDBACK ***');

        return back();
    }
}
