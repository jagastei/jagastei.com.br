<?php

declare(strict_types=1);

namespace App\States;

use Thunk\Verbs\State;

final class AccountState extends State
{
    public $account_id;

    public $wallet_id;

    public int $balance = 0;
}
