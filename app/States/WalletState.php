<?php

namespace App\States;

use Thunk\Verbs\State;

class WalletState extends State
{
    public int $balance = 0;
}
