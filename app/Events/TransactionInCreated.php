<?php

namespace App\Events;

use App\Models\Transaction;
use App\States\AccountState;
use Carbon\CarbonImmutable;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class TransactionInCreated extends Event
{
    public ?int $previous_balance = null;

    public ?int $current_balance = null;

    public function __construct(
        public string $title,
        public int $value,
        #[StateId(AccountState::class)]
        public int $account_id,
        public int $category_id,
        public CarbonImmutable $created_at,
    ) {
        $this->created_at = $created_at ?? now()->toImmutable();
    }

    public function apply(AccountState $accountState)
    {
        $this->previous_balance = $accountState->balance;

        $accountState->balance += $this->value;

        $this->current_balance = $accountState->balance;
    }

    public function handle(AccountState $accountState)
    {
        Transaction::create([
            'type' => 'IN',
            'title' => $this->title,
            'value' => $this->value,
            'account_id' => $accountState->account_id,
            'category_id' => $this->category_id,
            'created_at' => $this->created_at,
        ]);
    }
}
