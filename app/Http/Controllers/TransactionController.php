<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter');

        $categories = Category::query()
            ->ofUser(auth('web')->user())
            ->get();

        $transactions = QueryBuilder::for(Transaction::class)
            ->allowedFilters([
                AllowedFilter::exact('category', 'category_id'),
            ])
            ->ofUser(auth('web')->user())
            ->with([
                'category',
            ])
            ->orderByDesc('created_at')
            ->paginate($request->query('per_page', 10))
            ->appends(request()->query());

        return Inertia::render('Transactions/Index', [
            'filter' => $filter,
            'categories' => $categories,
            'transactions' => $transactions,
        ]);
    }
}
