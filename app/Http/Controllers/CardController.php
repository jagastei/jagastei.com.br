<?php

namespace App\Http\Controllers;

use App\Events\CardCreated;
use App\Http\Requests\StoreAccountRequest;
use Illuminate\Http\Request;
use App\Models\Card;
use Inertia\Inertia;

class CardController extends Controller
{
    public function index()
    {
        $cards = Card::query()
            ->get();

        return Inertia::render('Cards/Index', [
            'cards' => $cards,
        ]);
    }

    public function store(StoreAccountRequest $request)
    {
        $input = $request->validated();

        $userId = auth()->id();

        CardCreated::fire(
            user_id: $userId,
            bank_id: $input['bank'],
            name: $input['name'],
            initial_balance: $input['initial_balance'],
        );

        return back();
    }
}
