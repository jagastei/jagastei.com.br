<?php

namespace App\Events;

use App\Models\Category;
use Thunk\Verbs\Event;

class CategoryCreated extends Event
{
    public function __construct(
        public int $wallet_id,
        public string $name,
        public string $color,
        public string $type,
    ) {}

    public function handle()
    {
        Category::create([
            'wallet_id' => $this->wallet_id,
            'name' => $this->name,
            'color' => $this->color,
            'type' => $this->type,
        ]);
    }
}
