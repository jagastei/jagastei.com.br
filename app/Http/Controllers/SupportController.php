<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupportRequest;

class SupportController extends Controller
{
    public function store(SupportRequest $request)
    {
        $input = $request->validated();

        return back();
    }
}
