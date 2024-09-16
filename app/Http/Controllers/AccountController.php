<?php

namespace App\Http\Controllers;

use App\Events\AccountCreated;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Accounts/Index');
    }

    public function store(Request $request)
    {
        AccountCreated::fire(
            
        );
    }
}
