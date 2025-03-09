<?php

namespace App\Events;

use App\Models\Wallet;
use App\States\WalletState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class WalletCreated extends Event
{
    public function __construct(
        #[StateId(WalletState::class)]
        public ?int $wallet_id,
        public string $user_id,
        public string $name,
    ) {}

    public function handle()
    {
        $wallet = Wallet::create([
            'user_id' => $this->user_id,
            'name' => $this->name,
        ]);

        return $wallet;
    }
}
