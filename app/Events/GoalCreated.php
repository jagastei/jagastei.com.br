<?php

namespace App\Events;

use App\Models\Goal;
use Thunk\Verbs\Event;

class GoalCreated extends Event
{
    public function __construct(
        public int $user_id,
        public string $name,
        public string $total,
    )
    { }

    public function handle()
    {
        Goal::create([
            'user_id' => $this->user_id,
            'name' => $this->name,
            'total' => $this->total,
        ]);
    }
}
