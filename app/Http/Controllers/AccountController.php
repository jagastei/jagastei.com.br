<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\AccountCreated;
use App\Events\AccountDeleted;
use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\Account;
use App\Models\Bank;
use App\States\AccountState;
use Carbon\Carbon;
use Inertia\Inertia;

final class AccountController extends Controller
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
        $account->load([
            'bank',
        ]);

        $accountState = AccountState::load($account->id);

        $currentBalance = $accountState->balance / 100;

        $balanceByDay = collect();

        $startDateString = now()->startOfCentury()->format('Y-m-d');
        $endDateString = now()->endOfYear()->format('Y-m-d');

        $accountBalances = $accountState
            ->storedEvents()
            ->sortBy('datetime')
            ->whereBetween('datetime', [$startDateString, $endDateString])
            ->groupBy(fn ($event) => Carbon::parse($event->datetime)->format('Y-m-d'))
            ->map(fn ($events) => $events->last()->current_balance / 100);

        foreach ($accountBalances as $date => $balance) {
            if (! $balanceByDay->has($date)) {
                $balanceByDay[$date] = 0;
            }

            $balanceByDay[$date] += $balance;
        }

        $balanceByDay = $balanceByDay
            ->map(fn ($balance, $date) => [
                'name' => Carbon::parse($date)->translatedFormat('l, d F Y'),
                'Saldo' => $balance,
            ])
            ->values()
            ->all();

        return Inertia::render('Accounts/Show', [
            'account' => $account,
            'currentBalance' => $currentBalance,
            'balanceByDay' => $balanceByDay,
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
