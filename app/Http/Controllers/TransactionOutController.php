<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\TransactionOutCreated;
use App\Events\TransactionOutDeleted;
use App\Http\Requests\StoreImportTransactionOutRequest;
use App\Http\Requests\StoreTransactionOutRequest;
use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Prism;
use Prism\Prism\Schema\ArraySchema;
use Prism\Prism\Schema\EnumSchema;
use Prism\Prism\Schema\ObjectSchema;
use Prism\Prism\Schema\StringSchema;
use Prism\Prism\ValueObjects\Messages\Support\Image;
use Prism\Prism\ValueObjects\Messages\UserMessage;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

final class TransactionOutController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter');
        $sort = $request->query('sort', '-datetime');

        $categories = Category::query()
            ->ofWallet(auth('web')->user()->currentWallet)
            ->out()
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
            ->out()
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
            ->through(function ($transaction) {
                $transaction->value = $transaction->value / 100;

                return $transaction;
            })
            ->appends(request()->query());

        return Inertia::render('TransactionsOut/Index', [
            'filter' => $filter,
            'sort' => $sort,
            'categories' => $categories,
            'accounts' => $accounts,
            'transactions' => $transactions,
            'ai' => session()->get('ai'),
            'file_path' => session()->get('file_path'),
            'category' => session()->get('category'),
        ]);
    }

    public function store(StoreTransactionOutRequest $request)
    {
        $input = $request->validated();

        TransactionOutCreated::fire(
            title: $input['title'],
            value: $input['value'],
            account_id: (int) $input['account'],
            category_id: (int) $input['category'],
            datetime: now()->toImmutable(),
        );

        return back();
    }

    public function import(Request $request)
    {
        $request->validate([
            'files' => 'required|array|size:1',
            'files.*' => 'required|file',
        ]);

        $file = $request->file('files')[0];
        $fileBase64 = base64_encode($file->get());
        $fileMimeType = $file->getMimeType();

        // store image not public
        $filePath = $request->file('files')[0]->store('transactions');

        $categories = Category::query()
            ->ofWallet(auth('web')->user()->currentWallet)
            ->out()
            ->orderBy('name')
            ->get();

        try {
            $schema = new ObjectSchema(
                name: 'nota_fiscal',
                description: 'A nota fiscal é um documento financeiro que contém uma informações sobre a compra e uma lista de itens.',
                properties: [
                    new StringSchema('empresa', 'A empresa que emitiu a nota fiscal'),

                    new StringSchema('titulo', 'Descrição curta do gasto'),
                    new StringSchema('descricao', 'Descrição longa e detalhada do gasto'),

                    new ArraySchema(
                        name: 'itens',
                        description: 'Os itens da nota fiscal',
                        items: new ObjectSchema(
                            name: 'item',
                            description: 'O item da nota fiscal',
                            properties: [
                                new StringSchema('descricao', 'A descrição do item'),
                                new StringSchema('quantidade', 'A quantidade do item'),
                                new StringSchema('valor', 'O valor do item'),
                                new StringSchema('total', 'O total do item'),
                                new StringSchema('categoria', 'A categoria do item'),
                            ],
                        ),
                    ),

                    new StringSchema('total', 'O total da nota fiscal'),
                    new StringSchema('data', 'A data da nota fiscal no formato dd/mm/yyyy'),

                    new StringSchema('localizacao', 'A localização da nota fiscal'),

                    new EnumSchema('metodo_pagamento', 'O método de pagamento da nota fiscal', Transaction::METHODS),
                    new StringSchema('metodo_pagamento_sugerido', 'O método de pagamento sugerido'),

                    new EnumSchema('categoria', 'Tipo de gasto', $categories->pluck('name')->toArray()),
                    new StringSchema('categoria_sugerida', 'Tipo de gasto sugerido'),
                ],
                requiredFields: [],
            );

            $message = new UserMessage('Voce é um especialista em fazer a leitura de notas fiscais e retornar as informações em um formato estruturado. Retorne os valores formatados em reais. Você também deve categorizar os itens da nota fiscal.', [
                Image::fromBase64($fileBase64, $fileMimeType),
            ]);

            $response = Prism::structured()
                ->using(Provider::OpenAI, 'gpt-4o-mini')
                ->withSchema($schema)
                ->withMessages([$message])
                ->asStructured();

            session()->flash('ai', $response->structured);
            session()->flash('file_path', $filePath);
        } catch (Exception $e) {
            Log::error($e);

            return back();
        }
    }

    public function confirm(StoreImportTransactionOutRequest $request)
    {
        $input = $request->validated();

        TransactionOutCreated::fire(
            title: $input['title'],
            value: $input['value'],
            account_id: (int) $input['account'],
            category_id: (int) $input['category'],
            datetime: $input['datetime'],
            metadata: [
                'ai_data' => $input['ai'],
                'file_path' => $input['file_path'],
            ],
        );

        return back();
    }

    public function destroy(Transaction $transaction)
    {
        TransactionOutDeleted::fire(
            transaction_id: $transaction->id,
            value: $transaction->value,
            account_id: $transaction->account_id,
        );

        return back();
    }
}
