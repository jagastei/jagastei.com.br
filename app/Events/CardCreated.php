<?php

namespace App\Events;

use App\Models\Card;
use App\States\CardState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class CardCreated extends Event
{
    public function __construct(
        #[StateId(CardState::class)]
        public ?int $card_id = null,
        public ?int $account_id = null,
        public string $name,
        public int $limit,
    ) { }

    public function handle()
    {
        Card::create([
            'account_id' => $this->account_id,
            'name' => $this->name,
            'limit' => $this->limit,
        ]);
    }
}
