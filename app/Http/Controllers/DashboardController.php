<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use App\States\AccountState;
use App\States\WalletState;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth('web')->user();

        $startDate = $request->query('startDate');
        $endDate = $request->query('endDate');

        if ($startDate) {
            try {
                $startDate = Carbon::parse($startDate);
            } catch (InvalidFormatException $e) {
                $startDate = now()->startOfWeek(Carbon::MONDAY);
            }
        } else {
            $startDate = now()->startOfWeek(Carbon::MONDAY);
        }

        if ($endDate) {
            try {
                $endDate = Carbon::parse($endDate);
            } catch (InvalidFormatException $e) {
                $endDate = now()->endOfWeek(Carbon::MONDAY);
            }
        } else {
            $endDate = now()->endOfWeek(Carbon::MONDAY);
        }

        $startDateString = $startDate->format('Y-m-d');
        $endDateString = $endDate->format('Y-m-d');

        if (is_null($request->query('startDate')) && $startDateString === $endDateString) {
            $startDateString = $startDate->subWeek()->format('Y-m-d');
        }

        $datesSubquery = DB::raw("(SELECT generate_series('$startDateString'::date, '$endDateString'::date, interval '1 day') AS date) AS date_series");

        $transactionsSubquery = Transaction::query()
            ->ofWallet($user->currentWallet)
            ->out()
            ->orderBy('datetime')
            ->select(
                DB::raw('date(datetime) AS transaction_date'),
                'type',
                'value',
            )
            ->whereBetween(
                DB::raw('datetime'),
                [$startDate, $endDate]
            );

        $wastedByDay = DB::table($datesSubquery)
            ->leftJoinSub($transactionsSubquery, 't', function ($join) {
                $join->on('date_series.date', '=', 't.transaction_date');
            })
            ->select(
                DB::raw("TO_CHAR(date_series.date, 'TMDay, DD TMMonth YYYY') AS name"),
                DB::raw('SUM(t.value) AS "Saída"')
            )
            ->groupBy('date_series.date')
            ->orderBy('date_series.date')
            ->get();

        $wastedByDay->map(function ($day) {
            $day->{'Saída'} /= 100;
        });

        $wasteByDayTransactionCount = $transactionsSubquery->count();

        $wastedByCategory = Category::query()
            ->out()
            ->withCount([
                'transactions as transactions_count' => function ($query) use ($startDate, $endDate, $user) {
                    $query
                        ->ofWallet($user->currentWallet)
                        ->out()
                        ->whereBetween(
                            DB::raw('datetime'),
                            [$startDate, $endDate]
                        );
                },
            ])
            ->withSum([
                'transactions as transactions_sum_value' => function ($query) use ($startDate, $endDate, $user) {
                    $query
                        ->ofWallet($user->currentWallet)
                        ->out()
                        ->whereBetween(
                            DB::raw('datetime'),
                            [$startDate, $endDate]
                        );
                },
            ], 'value')
            ->withAvg([
                'transactions as transactions_avg_value' => function ($query) use ($startDate, $endDate, $user) {
                    $query
                        ->ofWallet($user->currentWallet)
                        ->out()
                        ->whereBetween(
                            DB::raw('datetime'),
                            [$startDate, $endDate]
                        );
                },
            ], 'value')
            ->withMin([
                'transactions as transactions_min_value' => function ($query) use ($startDate, $endDate, $user) {
                    $query
                        ->ofWallet($user->currentWallet)
                        ->out()
                        ->whereBetween(
                            DB::raw('datetime'),
                            [$startDate, $endDate]
                        );
                },
            ], 'value')
            ->withMax([
                'transactions as transactions_max_value' => function ($query) use ($startDate, $endDate, $user) {
                    $query
                        ->ofWallet($user->currentWallet)
                        ->out()
                        ->whereBetween(
                            DB::raw('datetime'),
                            [$startDate, $endDate]
                        );
                },
            ], 'value')
            ->get();

        $wastedByCategory->map(function ($category) {
            $category->transactions_sum_value /= 100;
        });

        $wastedByCategoryTotal = $wastedByCategory->sum('transactions_sum_value');

        $accounts = Account::query()
            ->ofWallet($user->currentWallet)
            ->get();

        $balanceByDay = collect();

        foreach ($accounts as $account) {
            $accountBalances = AccountState::load($account->id)
                ->storedEvents()
                ->sortBy('datetime')
                ->whereBetween('datetime', [$startDate, $endDate])
                ->groupBy(fn ($event) => Carbon::parse($event->datetime)->format('Y-m-d'))
                ->map(fn ($events) => $events->last()->current_balance / 100);

            foreach ($accountBalances as $date => $balance) {
                if (! $balanceByDay->has($date)) {
                    $balanceByDay[$date] = 0;
                }
                $balanceByDay[$date] += $balance;
            }
        }

        $balanceByDay = $balanceByDay
            ->map(fn ($balance, $date) => [
                'name' => Carbon::parse($date)->translatedFormat('l, d F Y'),
                'Saldo' => $balance,
            ])
            ->values()
            ->all();

        $currentBalance = WalletState::load($user->currentWallet->id)->balance / 100;

        return Inertia::render('Dashboard', [
            'startDate' => $startDateString,
            'endDate' => $endDateString,
            'currentBalance' => $currentBalance,
            'balanceByDay' => $balanceByDay,
            'wastedByDay' => $wastedByDay,
            'wasteByDayTransactionCount' => $wasteByDayTransactionCount,
            'wastedByCategory' => $wastedByCategory,
            'wastedByCategoryTotal' => $wastedByCategoryTotal,
        ]);
    }
}
