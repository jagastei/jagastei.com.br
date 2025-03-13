<?php

namespace App\Events;

use App\Models\User;
use App\States\UserState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class UserStartedTrial extends Event
{
    public function __construct(
        #[StateId(UserState::class)]
        public int $user_id,
    ) {}

    public function handle()
    {
        $user = User::find($this->user_id);

        $user->update([
            'trial_ends_at' => now()->addDays(7),
        ]);
    }
}
