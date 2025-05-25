<?php

namespace App\Events;

use App\Models\Account;
use App\States\AccountState;
use App\States\WalletState;
use Glhd\Bits\Snowflake;
use Thunk\Verbs\Event;

class AccountCreated extends Event
{
    public function __construct(
        public Snowflake $wallet_id,
        public ?string $bank_id,
        public string $name,
        public int $initial_balance = 0,
    ) {}

    public function apply(AccountState $account)
    {
        $account->wallet_id = $this->wallet_id;
        $account->balance = $this->initial_balance;

        WalletState::load($account->wallet_id)->balance += $this->initial_balance;
    }

    public function handle()
    {
        $account = Account::create([
            'wallet_id' => $this->wallet_id,
            'bank_id' => $this->bank_id,
            'name' => $this->name,
            'balance' => $this->initial_balance,
        ]);

        return $account;
    }
}
