<?php

namespace App\Events;

use App\Models\Card;
use Thunk\Verbs\Event;

class CardCreated extends Event
{
    public function __construct(
        public ?int $account_id,
        public string $name,
        public int $limit,
    ) {}

    public function handle()
    {
        $card = Card::create([
            'account_id' => $this->account_id,
            'name' => $this->name,
            'limit' => $this->limit,
        ]);

        return $card;
    }
}
