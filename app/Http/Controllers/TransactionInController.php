<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\TransactionInCreated;
use App\Events\TransactionInDeleted;
use App\Http\Requests\StoreTransactionInRequest;
use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class TransactionInController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter');
        $sort = $request->query('sort', '-datetime');

        $categories = Category::query()
            ->ofWallet(auth('web')->user()->currentWallet)
            ->in()
            ->orderBy('name')
            ->get();

        $accounts = Account::query()
            ->ofWallet(auth('web')->user()->currentWallet)
            ->with([
                'bank',
            ])
            ->orderBy('name')
            ->get();

        $transactions = QueryBuilder::for(Transaction::class)
            ->allowedFilters([
                'title',
                AllowedFilter::exact('category', 'category_id'),
            ])
            ->allowedSorts([
                'value',
                'datetime',
            ])
            ->defaultSort($sort)
            ->ofWallet(auth('web')->user()->currentWallet)
            ->in()
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
            ->paginate($request->query('per_page', 10))
            ->appends(request()->query());

        return Inertia::render('TransactionsIn/Index', [
            'filter' => $filter,
            'categories' => $categories,
            'accounts' => $accounts,
            'transactions' => $transactions,
            'ai' => session()->get('ai'),
        ]);
    }

    public function store(StoreTransactionInRequest $request)
    {
        $input = $request->validated();

        TransactionInCreated::fire(
            title: $input['title'],
            value: $input['value'],
            account_id: (int) $input['account'],
            category_id: (int) $input['category'],
            datetime: now()->toImmutable(),
        );

        return back();
    }

    public function destroy(Transaction $transaction)
    {
        TransactionInDeleted::fire(
            transaction_id: $transaction->id,
            value: $transaction->value,
            account_id: $transaction->account_id,
        );

        return back();
    }
}
