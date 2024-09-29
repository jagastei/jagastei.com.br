<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $transactions = Transaction::query()
            ->ofUser(auth('web')->user())
            ->with([
                'category',
            ])
            ->orderByDesc('created_at')
            ->paginate($request->query('per_page', 10));

        return Inertia::render('Transactions/Index', [
            'transactions' => $transactions,
        ]);
    }
}
