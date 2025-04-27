<?php

namespace App\Events;

use App\Models\Wallet;
use App\States\WalletState;
use Thunk\Verbs\Event;
use Thunk\Verbs\Support\StateCollection;

class WalletCreated extends Event
{
    public ?int $wallet_id = null;

    public function __construct(
        public string $user_id,
        public string $name,
    ) {
        $this->wallet_id ??= snowflake_id();
    }

    public function states(): StateCollection
    {
        return new StateCollection([
            WalletState::load($this->wallet_id),
        ]);
    }

    public function handle()
    {
        [$wallet_state] = $this->states();

        Wallet::create([
            'id' => $wallet_state->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
        ]);
    }
}
