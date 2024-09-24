<?php

namespace App\Events;

use App\Models\Category;
use Thunk\Verbs\Event;

class CategoryCreated extends Event
{
    public function __construct(
        public int $user_id,
        public string $name,
        public string $color,
    )
    { }

    public function handle()
    {
        Category::create([
            'user_id' => $this->user_id,
            'name' => $this->name,
            'color' => $this->color,
        ]);
    }
}
