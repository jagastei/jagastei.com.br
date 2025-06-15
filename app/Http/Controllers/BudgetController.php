<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\BudgetCreated;
use App\Http\Requests\StoreBudgetRequest;
use App\Http\Requests\UpdateBudgetRequest;
use App\Models\Budget;
use Illuminate\Http\Request;
use Inertia\Inertia;

final class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $budgets = Budget::query()
            ->ofWallet(auth('web')->user()->currentWallet)
            ->orderBy('name')
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

    public function update(UpdateBudgetRequest $request, Budget $budget)
    {
        $input = $request->validated();

        $budget->update($input);

        return back();
    }

    public function destroy(Budget $budget)
    {
        $budget->delete();

        return back();
    }
}
