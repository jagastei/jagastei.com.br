<?php

namespace App\Http\Controllers;

use App\Events\AccountCreated;
use App\Events\AccountDeleted;
use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
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
            ->orderBy('name')
            ->get()
            ->transform(function (Account $account) {
                $account->balance = $account->balance / 100;

                return $account;
            });

        $totalBalance = $accounts->sum('balance');

        return Inertia::render('Accounts/Index', [
            'banks' => $banks,
            'accounts' => $accounts,
            'totalBalance' => $totalBalance,
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

    public function show(Account $account)
    {
        return Inertia::render('Accounts/Show', [
            'account' => $account,
        ]);
    }

    public function update(UpdateAccountRequest $request, Account $account)
    {
        $input = $request->validated();

        $account->update($input);

        return back();
    }

    public function destroy(Account $account)
    {
        AccountDeleted::fire(
            account_id: $account->id,
        );

        return back();
    }
}
