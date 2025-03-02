<?php

namespace App\Events;

use App\Models\Wallet;
use Thunk\Verbs\Event;

class WalletCreated extends Event
{
    public function __construct(
        public string $user_id,
        public string $name,
    ) {}

    public function handle()
    {
        Wallet::create([
            'user_id' => $this->user_id,
            'name' => $this->name,
        ]);
    }
}
