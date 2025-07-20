<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Card;
use Illuminate\Http\Request;

final class SearchController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $search = $request->input('search');

        if (blank($search) || strlen($search) < 2) {
            return response()->json([
                'accounts' => [],
                'cards' => [],
            ]);
        }

        $accounts = Account::search($search)
            // ->orderBy('name')
            // ->where()
            ->query(function ($query) {
                $query
                    ->ofWallet(auth('web')->user()->currentWallet)
                    ->with([
                        'bank',
                    ]);
            })
            ->take(5)
            ->get();

        $cards = Card::search($search)
            // ->orderBy('name')
            // ->where()
            ->query(function ($query) {
                $query
                    ->ofWallet(auth('web')->user()->currentWallet)
                    ->with([
                        'account' => function ($query) {
                            $query->with([
                                'bank',
                            ]);
                        },
                        'brand',
                    ]);
            })
            ->take(5)
            ->get();

        return response()->json([
            'accounts' => $accounts,
            'cards' => $cards,
        ]);
    }
}
