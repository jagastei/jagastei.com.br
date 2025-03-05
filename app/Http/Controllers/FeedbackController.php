<?php

namespace App\Http\Controllers;

use App\Http\Requests\FeedbackRequest;

class FeedbackController extends Controller
{
    public function store(FeedbackRequest $request)
    {
        $input = $request->validated();

        return back();
    }
}
