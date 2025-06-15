<?php

namespace App\Events;

use App\Models\Account;
use App\Models\Transaction;
use App\States\AccountState;
use App\States\WalletState;
use Carbon\CarbonImmutable;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class TransactionOutDeleted extends Event
{
    public ?int $previous_balance = null;

    public ?int $current_balance = null;

    public CarbonImmutable $datetime;

    public function __construct(
        public int $transaction_id,
        public int $value,
        #[StateId(AccountState::class)]
        public int $account_id,
    ) {
        $this->datetime = now()->toImmutable();
    }

    public function apply(AccountState $account)
    {
        $this->previous_balance = $account->balance;
        $account->balance += $this->value;
        $this->current_balance = $account->balance;

        WalletState::load($account->wallet_id)->balance += $this->value;
    }

    public function handle()
    {
        $account = Account::find($this->account_id);

        $account->update([
            'balance' => $account->balance + $this->value,
        ]);

        Transaction::destroy($this->transaction_id);
    }
}
