<?php

namespace App\States;

use Carbon\Carbon;
use Thunk\Verbs\State;

class UserState extends State
{
    public Carbon|null $trial_started_at = null;
}
