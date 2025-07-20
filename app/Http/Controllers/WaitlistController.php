<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WaitlistController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        return back();
    }
}
