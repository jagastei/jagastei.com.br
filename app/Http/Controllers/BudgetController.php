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
            ->ofUser(auth('web')->user())
            ->get();

        return Inertia::render('Budgets/Index', [
            'budgets' => $budgets,
        ]);
    }

    public function store(StoreBudgetRequest $request)
    {
        $input = $request->validated();

        $userId = auth('web')->id();

        BudgetCreated::fire(
            user_id: $userId,
            name: $input['name'],
        );

        return back();
    }

    public function destroy(Budget $budget)
    {
        $budget->delete();

        return back();
    }
}
