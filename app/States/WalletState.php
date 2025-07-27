<?php

declare(strict_types=1);

namespace App\States;

use Thunk\Verbs\State;

final class WalletState extends State
{
    public $wallet_id;

    public int $balance = 0;
}
