<?php

namespace App\States;

use Thunk\Verbs\State;

class AccountState extends State
{
    public int $account_id;

    public int $balance = 0;
}
