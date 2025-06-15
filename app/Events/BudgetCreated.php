<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Budget;
use Thunk\Verbs\Event;

final class BudgetCreated extends Event
{
    public function __construct(
        public int $wallet_id,
        public string $name,
        public int $total,
    ) {}

    public function handle()
    {
        Budget::create([
            'wallet_id' => $this->wallet_id,
            'name' => $this->name,
            'total' => $this->total,
        ]);
    }
}
