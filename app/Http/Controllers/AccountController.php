<?php

namespace App\Http\Controllers;

use App\Events\AccountCreated;
use App\Http\Requests\StoreAccountRequest;
use App\Models\Account;
use App\Models\Bank;
use Inertia\Inertia;

class AccountController extends Controller
{
    public function index()
    {
        $banks = Bank::query()
            ->enabled()
            ->orderBy('code')
            ->get();

        $accounts = Account::query()
            ->ofWallet(auth('web')->user()->currentWallet)
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

        $walletId = auth('web')->user()->currentWallet->id;

        AccountCreated::fire(
            wallet_id: $walletId,
            bank_id: $input['bank'],
            name: $input['name'],
            initial_balance: $input['initial_balance'],
        );

        return back();
    }

    public function destroy(Account $account)
    {
        $account->delete();

        return back();
    }
}
