<?php

namespace App\Events;

use App\States\UserState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class UserStartedTrial extends Event
{
    #[StateId(UserState::class)]
    public ?int $user_id = null;

    public function authorize()
    {

    }

    public function validate(UserState $user)
    {
        $this->assert($user->trial_started_at === null, 'This user has not started a trial yet.');
    }

    public function apply(UserState $user)
    {
        $user->trial_started_at = now();
    }

    public function fired()
    {

    }

    public function handle()
    {
        
    }
}
