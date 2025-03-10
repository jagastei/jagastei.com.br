<?php

namespace App\Http\Controllers;

use App\Events\CardCreated;
use App\Http\Requests\StoreCardRequest;
use App\Http\Requests\UpdateCardRequest;
use App\Models\Account;
use App\Models\Brand;
use App\Models\Card;
use Inertia\Inertia;

class CardController extends Controller
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
            ->get();

        return Inertia::render('Cards/Index', [
            'brands' => $brands,
            'accounts' => $accounts,
            'cards' => $cards,
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

    public function update(UpdateCardRequest $request, Card $card)
    {
        $input = $request->validated();

        $card->update($input);

        return back();
    }

    public function destroy(Card $card)
    {
        $card->delete();

        return back();
    }
}
