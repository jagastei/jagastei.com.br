<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Category;
use App\Models\Wallet;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Prism\Prism\Enums\Provider;
use Prism\Prism\Prism;
use Prism\Prism\Schema\EnumSchema;
use Prism\Prism\Schema\NumberSchema;
use Prism\Prism\Schema\ObjectSchema;
use Prism\Prism\Schema\StringSchema;
use Prism\Prism\ValueObjects\Messages\SystemMessage;
use Prism\Prism\ValueObjects\Messages\UserMessage;

final class HandleWhatsappMessage implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public array $input
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::channel('whatsapp')->info('HandleWhatsappMessage', $this->input);

        return;

        if ($this->input['event'] !== 'messages.upsert') {
            return;
        }

        $messageType = $this->input['data']['messageType'];

        if ($messageType !== 'conversation') {
            return;
        }

        $fromMe = $this->input['data']['key']['fromMe'];

        if (! $fromMe) {
            return;
        }

        $message = $this->input['data']['message']['conversation'];
        // $imageMessage = $this->input['data']['message']['imageMessage'];

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
                    $message,
                ])
                ->asStructured();

        } catch (Exception $e) {
            Log::channel('whatsapp')->error($e);
        }
    }
}
