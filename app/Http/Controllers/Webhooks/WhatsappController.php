<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Wallet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Prism;
use Prism\Prism\Schema\EnumSchema;
use Prism\Prism\Schema\NumberSchema;
use Prism\Prism\Schema\ObjectSchema;
use Prism\Prism\Schema\StringSchema;
use Prism\Prism\ValueObjects\Messages\SystemMessage;
use Prism\Prism\ValueObjects\Messages\UserMessage;

class WhatsappController extends Controller
{
    public function store(Request $request)
    {
        return;

        Log::info('Webhook recebido', $request->all());

        $input = $request->all();

        if($input['event'] !== 'messages.upsert') {
            return response()->noContent(200);
        }

        $messageType = $input['data']['messageType'];

        if($messageType !== 'conversation') {
            return response()->noContent(200);
        }

        $fromMe = $input['data']['key']['fromMe'];

        if(!$fromMe) {
            return response()->noContent(200);
        }

        $message = $input['data']['message']['conversation'];

        Log::info($message);

        $categories = Category::query()
            // ->ofWallet(auth('web')->user()->currentWallet)
            ->ofWallet(Wallet::first())
            ->out()
            ->orderBy('name')
            ->get();

        $schema = new ObjectSchema(
            name: 'transacao',
            description: 'Dados da transação',
            properties: [
                new StringSchema('titulo', 'Título da transação'),
                new NumberSchema('total', 'O total da transação em centavos'),
                new EnumSchema('categoria', 'Tipo de transação', $categories->pluck('name')->toArray(), true),
                new StringSchema('categoria_sugerida', 'Tipo de transação sugerido'),
            ],
            requiredFields: [
                'titulo',
                'total',
                'categoria',
                'categoria_sugerida',
            ]
        );

        $prompt = new SystemMessage('Você é um assistente especializado em extrair informações de transações financeiras. Sua tarefa é interpretar a mensagem do usuário e retornar um objeto JSON.');
        $message = new UserMessage($message);
        
        try {
            $response = Prism::structured()
                ->using(Provider::OpenAI, 'gpt-4o')
                ->withSchema($schema)
                ->withMessages([
                    $prompt,
                    $message
                ])
                ->asStructured();

            Log::info($response->structured);
        } catch (Exception $e) {
            Log::error($e);
        }

        return response()->noContent(200);
    }
}
