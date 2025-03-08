<?php

namespace App\Events;

use App\Models\Transaction;
use App\States\AccountState;
use Carbon\CarbonImmutable;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class TransactionCreated extends Event
{
    public ?int $previous_balance = null;

    public ?int $current_balance = null;

    public CarbonImmutable $created_at;

    public function __construct(
        public string $type,
        public string $title,
        public int $value,
        #[StateId(AccountState::class)]
        public int $account_id,
        public int $category_id,
    ) {
        $this->created_at = now()->toImmutable();
    }

    public function apply(AccountState $account)
    {
        $this->previous_balance = $account->balance;

        if ($this->type === 'IN') {
            $account->balance += $this->value;
        } else {
            $account->balance -= $this->value;
        }

        $this->current_balance = $account->balance;
    }

    public function handle()
    {
        Transaction::create([
            'type' => $this->type,
            'title' => $this->title,
            'value' => $this->value,
            'account_id' => $this->account_id,
            'category_id' => $this->category_id,
        ]);
    }
}
