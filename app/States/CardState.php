<?php

declare(strict_types=1);

namespace App\States;

use Thunk\Verbs\State;

final class CardState extends State
{
    public $card_id;

    public $account_id;

    public int $limit = 0;
}
