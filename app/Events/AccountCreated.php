<?php

namespace App\Events;

use App\Models\Account;
use App\States\AccountState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;
use Thunk\Verbs\Support\StateCollection;

class AccountCreated extends Event
{
    public function __construct(
        #[StateId(AccountState::class)]
        public ?int $account_id,
        public int $wallet_id,
        public ?string $bank_id,
        public string $name,
        public int $initial_balance = 0,
    ) {}

    public function states(): StateCollection
    {
        $this->account_id ??= snowflake_id();

        return new StateCollection([
            AccountState::load($this->account_id),
        ]);
    }

    public function apply(AccountState $account)
    {
        $account->balance = $this->initial_balance;
    }

    public function handle()
    {
        [$account_state] = $this->states();

        $account = Account::create([
            'id' => $account_state->id,
            'wallet_id' => $this->wallet_id,
            'bank_id' => $this->bank_id,
            'name' => $this->name,
            'balance' => $this->initial_balance,
        ]);

        return $account;
    }
}
