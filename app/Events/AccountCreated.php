<?php

namespace App\Events;

use App\Models\Account;
use App\States\AccountState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class AccountCreated extends Event
{
    public function __construct(
        #[StateId(AccountState::class)]
        public ?int $account_id,
        public int $wallet_id,
        public ?int $bank_id,
        public string $name,
        public int $initial_balance = 0,
    ) {}

    public function apply(AccountState $account)
    {
        $account->balance = $this->initial_balance;
    }

    public function handle()
    {
        Account::create([
            'wallet_id' => $this->wallet_id,
            'bank_id' => $this->bank_id,
            'name' => $this->name,
            'balance' => $this->initial_balance,
        ]);
    }
}
