<?php

namespace App\States;

use Thunk\Verbs\State;

class AccountState extends State
{
    public $wallet_id;

    public int $balance = 0;
}
