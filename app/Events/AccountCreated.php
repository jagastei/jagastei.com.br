<?php

namespace App\Events;

use App\Models\Account;
use App\States\AccountState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class AccountCreated extends Event
{
    #[StateId(AccountState::class)]
    public ?int $account_id = null;

    public int $user_id;

    public int $initial_deposit = 0;

    public function authorize()
    {

    }

    public function validate(AccountState $account)
    {

    }

    public function apply(AccountState $account)
    {
        $account->balance = $this->initial_deposit;
    }

    public function fired()
    {

    }

    public function handle()
    {
        Account::create([
            'user_id' => $this->user_id,
            'balance' => $this->initial_deposit,
        ]);

        // Verbs::unlessReplaying(fn () => Mail::send(new WelcomeEmail($this->user_id)));
    }
}
