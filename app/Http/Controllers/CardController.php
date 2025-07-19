<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\CardCreated;
use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\UpdateCardRequest;
use App\Models\Account;
use App\Models\Brand;
use App\Models\Card;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

final class CardController extends Controller
{
    public function index()
    {
        $brands = Brand::query()
            ->enabled()
            ->orderBy('name')
            ->get();

        $accounts = Account::query()
            ->ofWallet(auth('web')->user()->currentWallet)
            ->with([
                'bank',
            ])
            ->orderBy('name')
            ->get();

        $cards = Card::query()
            ->ofWallet(auth('web')->user()->currentWallet)
            ->with([
                'account' => function ($query) {
                    $query->with([
                        'bank',
                    ]);
                },
                'brand',
            ])
            ->orderBy('name')
            ->get()
            ->map(function ($card) {
                $card->limit = $card->limit / 100;

                return $card;
            });

        $totalLimit = $cards->sum('limit');

        return Inertia::render('Cards/Index', [
            'brands' => $brands,
            'accounts' => $accounts,
            'cards' => $cards,
            'totalLimit' => $totalLimit,

            'card' => fn () => request()->whenHas('card', function (string $card) {
                return Card::find($card)
                    ?->load([
                        'account' => function ($query) {
                            $query->with([
                                'bank',
                            ]);
                        },
                        'brand',
                    ]);
            }, fn () => null),
        ]);
    }

    public function store(StoreCardRequest $request)
    {
        $input = $request->validated();

        CardCreated::fire(
            account_id: $input['account_id'],
            name: $input['name'],
            limit: $input['limit'],
        );

        return back();
    }

    public function show(Card $card)
    {
        $card->load([
            'account' => function ($query) {
                $query->with([
                    'bank',
                ]);
            },
            'brand',
        ]);

        $startDate = now()->startOfMonth();
        $endDate = now()->endOfMonth();

        $startDateString = $startDate->format('Y-m-d');
        $endDateString = $endDate->format('Y-m-d');

        $datesSubquery = DB::raw("(SELECT generate_series('$startDateString'::date, '$endDateString'::date, interval '1 day') AS date) AS date_series");

        $transactionsSubquery = Transaction::query()
            ->ofCard($card)
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
                DB::raw("TO_CHAR(date_series.date, 'YYYY-MM-DD') AS name"),
                DB::raw('SUM(t.value) AS "Saída"')
            )
            ->groupBy('date_series.date')
            ->orderBy('date_series.date')
            ->get();

        $wastedByDay->map(function ($day) {
            $day->name = Carbon::parse($day->name)->translatedFormat('l, d F Y');
            $day->{'Saída'} /= 100;
        });

        $totalWasted = $wastedByDay->sum('Saída');

        return Inertia::render('Cards/Show', [
            'card' => $card,
            'totalWasted' => $totalWasted,
            'wastedByDay' => $wastedByDay,
        ]);
    }

    public function update(UpdateCardRequest $request, Card $card)
    {
        $input = $request->validated();

        $card->update($input);

        return back();
    }

    public function updateName(Request $request, Card $card)
    {
        $input = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:30'],
        ]);

        $card->update($input);

        return back();
    }

    public function destroy(Card $card)
    {
        $card->delete();

        return back();
    }
}
