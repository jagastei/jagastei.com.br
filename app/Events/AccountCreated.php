<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Account;
use App\States\AccountState;
use App\States\WalletState;
use Glhd\Bits\Snowflake;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

final class AccountCreated extends Event
{
    public function __construct(
        #[StateId(AccountState::class)]
        public ?int $account_id,
        public Snowflake $wallet_id,
        public ?string $bank_id,
        public string $name,
        public int $initial_balance = 0,
    ) {}

    public function apply(AccountState $account)
    {
        $account->account_id = $this->account_id;
        $account->wallet_id = $this->wallet_id;
        $account->balance = $this->initial_balance;

        WalletState::load($account->wallet_id)->balance += $this->initial_balance;
    }

    public function handle()
    {
        return Account::create([
            'id' => $this->account_id,
            'wallet_id' => $this->wallet_id,
            'bank_id' => $this->bank_id,
            'name' => $this->name,
            'balance' => $this->initial_balance,
        ]);
    }
}
