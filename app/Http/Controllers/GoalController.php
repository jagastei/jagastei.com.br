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
            ->ofUser(auth('web')->user())
            ->get();

        return Inertia::render('Goals/Index', [
            'goals' => $goals,
        ]);
    }

    public function store(StoreGoalRequest $request)
    {
        $input = $request->validated();

        $userId = auth('web')->id();

        GoalCreated::fire(
            user_id: $userId,
            name: $input['name'],
        );

        return back();
    }

    public function destroy(Goal $goal)
    {
        $goal->delete();

        return back();
    }
}
