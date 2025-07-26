<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Category;
use Thunk\Verbs\Event;

final class CategoryCreated extends Event
{
    public function __construct(
        public string $wallet_id,
        public string $name,
        public string $color,
        public string $type,
    ) {}

    public function handle()
    {
        return Category::create([
            'wallet_id' => $this->wallet_id,
            'name' => $this->name,
            'color' => $this->color,
            'type' => $this->type,
        ]);
    }
}
