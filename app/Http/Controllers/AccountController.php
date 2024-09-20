<?php

namespace App\Http\Controllers;

use App\Events\AccountCreated;
use App\Http\Requests\StoreAccountRequest;
use App\Models\Account;
use App\Models\Bank;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountController extends Controller
{
    public function index()
    {
        $banks = Bank::query()
            ->get();

        $accounts = Account::query()
            ->with([
                'bank',
            ])
            ->get();

        return Inertia::render('Accounts/Index', [
            'banks' => $banks,
            'accounts' => $accounts,
        ]);
    }

    public function store(StoreAccountRequest $request)
    {
        $input = $request->validated();

        $userId = auth()->id();

        AccountCreated::fire(
            user_id: $userId,
            bank_id: $input['bank'],
            name: $input['name'],
            initial_balance: $input['initial_balance'],
        );

        return back();
    }
}
