<?php

namespace App\Http\Controllers;

use App\Events\BudgetCreated;
use App\Http\Requests\StoreBudgetRequest;
use App\Models\Budget;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $budgets = Budget::query()
            ->ofWallet(auth('web')->user()->currentWallet)
            ->get();

        return Inertia::render('Budgets/Index', [
            'budgets' => $budgets,
        ]);
    }

    public function store(StoreBudgetRequest $request)
    {
        $input = $request->validated();

        $walletId = auth('web')->user()->currentWallet->id;

        BudgetCreated::fire(
            wallet_id: $walletId,
            name: $input['name'],
            total: $input['total'],
        );

        return back();
    }

    public function destroy(Budget $budget)
    {
        $budget->delete();

        return back();
    }
}
