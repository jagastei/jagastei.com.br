<?php

namespace App\Http\Controllers;

use App\Events\GoalCreated;
use App\Http\Requests\StoreGoalRequest;
use App\Models\Goal;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GoalController extends Controller
{
    public function index(Request $request)
    {
        $goals = Goal::query()
            ->ofWallet(auth('web')->user()->currentWallet)
            ->get();

        return Inertia::render('Goals/Index', [
            'goals' => $goals,
        ]);
    }

    public function store(StoreGoalRequest $request)
    {
        $input = $request->validated();

        $walletId = auth('web')->user()->currentWallet->id;

        GoalCreated::fire(
            wallet_id: $walletId,
            name: $input['name'],
            total: $input['total'],
        );

        return back();
    }

    public function destroy(Goal $goal)
    {
        $goal->delete();

        return back();
    }
}
