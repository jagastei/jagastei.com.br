<?php

namespace App\Http\Controllers;

use App\Events\TransactionCreated;
use App\Http\Requests\StoreTransactionRequest;
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
                'title',
                AllowedFilter::exact('category', 'category_id'),
            ])
            ->ofUser(auth('web')->user())
            ->with([
                'category',
                'account' => function ($query) {
                    $query->with([
                        'bank',
                    ]);
                },
                'card' => function ($query) {
                    $query->with([
                        'account' => function ($query) {
                            $query->with([
                                'bank',
                            ]);
                        },
                        'brand',
                    ]);
                },
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

    public function store(StoreTransactionRequest $request)
    {
        $input = $request->validated();

        $userId = auth('web')->id();

        TransactionCreated::fire(
            user_id: $userId,
        );

        return back();
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return back();
    }
}
