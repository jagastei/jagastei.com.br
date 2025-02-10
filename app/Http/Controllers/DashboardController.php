<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $startDate = now()->subYear()->subWeek()->startOfWeek(Carbon::MONDAY);
        $endDate = now()->subWeek()->endOfWeek(Carbon::SUNDAY);

        $startDate = now()->subMonths(6)->startOfMonth();
        $endDate = now()->subMonths(6)->endOfMonth();

        $startDateString = $startDate->format('Y-m-d');
        $endDateString = $endDate->format('Y-m-d');

        DB::statement("SET lc_time = 'pt_BR.UTF-8'");

        $datesSubquery = DB::raw("(SELECT generate_series('$startDateString'::date, '$endDateString'::date, interval '1 day') AS date) AS date_series");

        $transactionsSubquery = Transaction::query()
            ->ofUser(auth('web')->user())
            ->select(
                DB::raw('date(created_at) AS transaction_date'),
                'type',
                'value',
            )
            ->whereBetween(
                DB::raw('created_at'),
                [$startDate, $endDate]
            );

        $overview2 = DB::table($datesSubquery)
            ->leftJoinSub($transactionsSubquery, 't', function ($join) {
                $join->on('date_series.date', '=', 't.transaction_date');
            })
            ->select(
                DB::raw("TO_CHAR(date_series.date, 'TMDay, DD TMMonth YYYY') AS name"),
                DB::raw("CAST(COALESCE(SUM(CASE WHEN t.type = 'IN' THEN t.value ELSE 0 END), 0) AS INTEGER) AS \"Entrada\""),
                DB::raw("CAST(COALESCE(SUM(CASE WHEN t.type = 'OUT' THEN t.value ELSE 0 END), 0) AS INTEGER) AS \"Saída\"")
            )
            ->groupBy('date_series.date')
            ->orderBy('date_series.date')
            ->get();

        $overview3 = Category::query()
            ->withCount([
                'transactions as transactions_count' => function ($query) {
                    $query
                        ->ofUser(auth('web')->user())
                        ->where('type', 'OUT');
                },
            ])
            ->withSum([
                'transactions as transactions_sum_value' => function ($query) {
                    $query
                        ->ofUser(auth('web')->user())
                        ->where('type', 'OUT');
                },
            ], 'value')
            ->withAvg([
                'transactions as transactions_avg_value' => function ($query) {
                    $query
                        ->ofUser(auth('web')->user())
                        ->where('type', 'OUT');
                },
            ], 'value')
            ->withMin([
                'transactions as transactions_min_value' => function ($query) {
                    $query
                        ->ofUser(auth('web')->user())
                        ->where('type', 'OUT');
                },
            ], 'value')
            ->withMax([
                'transactions as transactions_max_value' => function ($query) {
                    $query
                        ->ofUser(auth('web')->user())
                        ->where('type', 'OUT');
                },
            ], 'value')
            ->get();

        return Inertia::render('Dashboard', [
            'overview2' => $overview2,
            'overview3' => $overview3,
        ]);
    }
}
