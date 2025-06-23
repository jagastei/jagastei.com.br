<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\CardCreated;
use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\UpdateCardRequest;
use App\Models\Account;
use App\Models\Brand;
use App\Models\Card;
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
        return Inertia::render('Cards/Show', [
            'card' => $card,
        ]);
    }

    public function update(UpdateCardRequest $request, Card $card)
    {
        $input = $request->validated();

        dd($input);

        $card->update($input);

        return back();
    }

    public function destroy(Card $card)
    {
        $card->delete();

        return back();
    }
}
