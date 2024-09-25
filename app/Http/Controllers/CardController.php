<?php

namespace App\Http\Controllers;

use App\Events\CardCreated;
use App\Http\Requests\StoreCardRequest;
use App\Models\Account;
use App\Models\Brand;
use Illuminate\Http\Request;
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
            ->ofUser(auth('web')->user())
            ->with([
                'bank',
            ])
            ->get();

        $cards = Card::query()
            ->ofUser(auth('web')->user())
            ->with([
                'account' => function ($query) {
                    $query->with([
                        'bank'
                    ]);
                },
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

        $userId = auth('web')->id();

        CardCreated::fire(
            user_id: $userId,
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
