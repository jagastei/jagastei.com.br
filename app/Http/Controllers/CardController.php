<?php

namespace App\Http\Controllers;

use App\Events\CardCreated;
use App\Http\Requests\StoreCardRequest;
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

        $walletId = auth('web')->user()->currentWallet->id;

        CardCreated::fire(
            wallet_id: $walletId,
            name: $input['name'],
        );

        return back();
    }

    public function destroy(Card $card)
    {
        $card->delete();

        return back();
    }
}
