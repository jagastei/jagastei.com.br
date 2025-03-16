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

class TransactionOutCreated extends Event
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
        public ?array $metadata = [],
    ) {}

    public function apply(AccountState $account)
    {
        $this->previous_balance = $account->balance;

        $account->balance -= $this->value;

        WalletState::load($account->wallet_id)->balance -= $this->value;

        $this->current_balance = $account->balance;
    }

    public function handle()
    {
        $account = Account::find($this->account_id);

        $account->update([
            'balance' => $account->balance - $this->value,
        ]);

        $transaction = Transaction::create([
            'type' => 'OUT',
            'title' => $this->title,
            'value' => $this->value,
            'account_id' => $account->id,
            'category_id' => $this->category_id,
            'datetime' => $this->datetime,
            'metadata' => $this->metadata,
        ]);

        return $transaction;
    }
}
