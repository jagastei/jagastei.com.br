<?php

namespace App\States;

use Carbon\Carbon;
use Thunk\Verbs\State;

class UserState extends State
{
    public ?Carbon $trial_ends_at = null;
}
