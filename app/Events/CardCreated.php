<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Card;
use Thunk\Verbs\Event;

final class CardCreated extends Event
{
    public function __construct(
        public ?int $account_id,
        public string $name,
        public int $limit,
    ) {}

    public function handle()
    {
        Card::create([
            'account_id' => $this->account_id,
            'name' => $this->name,
            'limit' => $this->limit,
        ]);
    }
}
