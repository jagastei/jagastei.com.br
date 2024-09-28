<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $startDate = now()->subWeek()->startOfWeek(Carbon::MONDAY);
        $endDate = now()->subWeek()->endOfWeek(Carbon::SUNDAY);

        $startDateString = $startDate->format('Y-m-d');
        $endDateString = $endDate->format('Y-m-d');

        $datesSubquery = DB::raw("(SELECT generate_series('$startDateString'::date, '$endDateString'::date, interval '1 day') AS date) AS date_series");

        $overview = Transaction::query()
            ->ofUser(auth('web')->user())
            ->select(
                DB::raw("TO_CHAR(created_at, 'TMMonth YYYY') AS month"),
                DB::raw('SUM(value) AS total_value')
            )
            ->groupBy('month')
            ->orderByRaw("MIN(created_at)")
            ->get();

        $overview = $overview->map(function ($item) {
            $item['total_value'] = (int) $item['total_value'];

            return $item;
        });

        $transactionsSubquery = Transaction::query()
            ->ofUser(auth('web')->user())
            ->select(
                // DB::raw("TO_CHAR(created_at, 'TMMonth YYYY') AS name"),
                // DB::raw("TO_CHAR(created_at, 'DD TMMonth YYYY') AS name"),
                // DB::raw("CAST(SUM(CASE WHEN type = 'IN' THEN value ELSE 0 END) AS INTEGER) AS total_in"),
                // DB::raw("CAST(SUM(CASE WHEN type = 'OUT' THEN value ELSE 0 END) AS INTEGER) AS total_out")
                DB::raw('date(created_at) AS transaction_date'),
                'type',
                'value',
            )
            ->whereBetween(
                DB::raw("created_at"),
                [$startDate, $endDate]
            );
            // ->groupBy('name')
            // ->orderByRaw("MIN(created_at)")
            // ->get();
        
            $overview2 = DB::table($datesSubquery)
            ->leftJoinSub($transactionsSubquery, 't', function($join) {
                $join->on('date_series.date', '=', 't.transaction_date');
            })
            ->select(
                DB::raw("TO_CHAR(date_series.date, 'DD TMMonth YYYY') AS name"),
                DB::raw("CAST(COALESCE(SUM(CASE WHEN t.type = 'IN' THEN t.value ELSE 0 END), 0) AS INTEGER) AS total_in"),
                DB::raw("CAST(COALESCE(SUM(CASE WHEN t.type = 'OUT' THEN t.value ELSE 0 END), 0) AS INTEGER) AS total_out")
            )
            ->groupBy('date_series.date')
            ->orderBy('date_series.date')
            ->get();

        // dd($overview2);

        return Inertia::render('Dashboard', [
            'overview' => $overview,
            'overview2' => $overview2,
        ]);
    }
}
