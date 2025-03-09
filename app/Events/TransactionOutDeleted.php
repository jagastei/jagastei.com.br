<?php

namespace App\Events;

use App\Models\Transaction;
use App\States\AccountState;
use Carbon\CarbonImmutable;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class TransactionOutDeleted extends Event
{
    public ?int $previous_balance = null;

    public ?int $current_balance = null;

    public CarbonImmutable $created_at;

    public function __construct(
        public int $transaction_id,
        public int $value,
        #[StateId(AccountState::class)]
        public int $account_id,
    ) {
        $this->created_at = now()->toImmutable();
    }

    public function apply(AccountState $account)
    {
        $this->previous_balance = $account->balance;

        $account->balance += $this->value;

        $this->current_balance = $account->balance;
    }

    public function handle()
    {
        Transaction::destroy($this->transaction_id);
    }
}
