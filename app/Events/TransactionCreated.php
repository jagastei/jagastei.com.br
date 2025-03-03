<?php

namespace App\Events;

use App\Models\Transaction;
use App\States\AccountState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class TransactionCreated extends Event
{
    public function __construct(
        public string $type,
        #[StateId(AccountState::class)]
        public int $account_id,
        public int $category_id,
        public int $value,
    ) {}

    public function apply(AccountState $account)
    {
        if ($this->type === 'IN') {
            $account->balance += $this->value;
        } else {
            $account->balance -= $this->value;
        }
    }

    public function handle()
    {
        Transaction::create([
            'type' => $this->type,
            'value' => $this->value,
            'account_id' => $this->account_id,
            'category_id' => $this->category_id,
        ]);
    }
}
