<?php

namespace App\Http\Controllers;

use App\Events\TransactionCreated;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Category;
use App\Models\Transaction;
use EchoLabs\Prism\Enums\Provider;
use EchoLabs\Prism\Prism;
use EchoLabs\Prism\Schema\ArraySchema;
use EchoLabs\Prism\Schema\ObjectSchema;
use EchoLabs\Prism\Schema\StringSchema;
use EchoLabs\Prism\ValueObjects\Messages\Support\Image;
use EchoLabs\Prism\ValueObjects\Messages\UserMessage;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TransactionInController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->query('filter');

        $categories = Category::query()
            ->ofUser(auth('web')->user())
            ->in()
            ->get();

        $transactions = QueryBuilder::for(Transaction::class)
            ->allowedFilters([
                'title',
                AllowedFilter::exact('category', 'category_id'),
            ])
            ->ofUser(auth('web')->user())
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
            ->orderByDesc('created_at')
            ->paginate($request->query('per_page', 10))
            ->appends(request()->query());

        return Inertia::render('TransactionsIn/Index', [
            'filter' => $filter,
            'categories' => $categories,
            'transactions' => $transactions,
            'ai' => session()->get('ai'),
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

    public function import(Request $request)
    {
        $request->validate([
            'files' => 'required|array|size:1',
            'files.*' => 'required|file',
        ]);

        $file = $request->file('files')[0];
        $fileBase64 = base64_encode($file->get());
        $fileMimeType = $file->getMimeType();

        try {
            $schema = new ObjectSchema(
                name: 'nota_fiscal',
                description: 'A nota fiscal é um documento financeiro que contém uma informações sobre a compra e uma lista de itens.',
                properties: [
                    new StringSchema('empresa', 'A empresa que emitiu a nota fiscal'),
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
                    new StringSchema('data', 'A data da nota fiscal'),
                    new StringSchema('localizacao', 'A localização da nota fiscal'),
                    new StringSchema('metodo_pagamento', 'O método de pagamento da nota fiscal'),
                    new StringSchema('categoria', 'Tipo de gasto'),
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
                ->generate();

            session()->flash('ai', $response->structured);
        } catch (Exception $e) {
            dd($e);
            Log::error($e);

            return back();
        }
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return back();
    }
}
