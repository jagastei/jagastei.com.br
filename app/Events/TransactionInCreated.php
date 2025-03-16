<?php

namespace App\Events;

use App\Models\Account;
use App\Models\Transaction;
use App\States\AccountState;
use App\States\CategoryState;
use App\States\WalletState;
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
        // #[StateId(CategoryState::class)]
        public int $category_id,
        public CarbonImmutable $datetime,
    ) {}

    public function apply(AccountState $accountState)
    {
        $this->previous_balance = $accountState->balance;

        $accountState->balance += $this->value;

        WalletState::load($accountState->wallet_id)->balance += $this->value;

        $this->current_balance = $accountState->balance;
    }

    public function handle()
    {
        $account = Account::find($this->account_id);

        $account->update([
            'balance' => $account->balance + $this->value,
        ]);

        Transaction::create([
            'type' => 'IN',
            'title' => $this->title,
            'value' => $this->value,
            'account_id' => $account->id,
            'category_id' => $this->category_id,
            'datetime' => $this->datetime,
        ]);
    }
}
