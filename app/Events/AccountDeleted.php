<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Account;
use App\States\AccountState;
use App\States\WalletState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

final class AccountDeleted extends Event
{
    public function __construct(
        #[StateId(AccountState::class)]
        public ?int $account_id,
    ) {}

    public function apply(AccountState $account)
    {
        WalletState::load($account->wallet_id)->balance -= $account->balance;
    }

    public function handle()
    {
        $account = Account::find($this->account_id);
        $account->delete();
    }
}
