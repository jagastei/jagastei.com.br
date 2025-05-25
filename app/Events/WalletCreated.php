<?php

namespace App\Events;

use App\Models\Wallet;
use App\States\WalletState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;
use Thunk\Verbs\Support\StateCollection;

class WalletCreated extends Event
{
    public function __construct(
        #[StateId(WalletState::class)]
        public ?int $wallet_id = null,
        public string $user_id,
        public string $name,
    ) {}

    public function handle()
    {
        Wallet::create([
            'id' => $this->wallet_id,
            'user_id' => $this->user_id,
            'name' => $this->name,
        ]);
    }
}
