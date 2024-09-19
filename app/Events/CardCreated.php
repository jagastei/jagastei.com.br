<?php

namespace App\Events;

use App\Models\Card;
use App\States\CardState;
use Thunk\Verbs\Attributes\Autodiscovery\StateId;
use Thunk\Verbs\Event;

class CardCreated extends Event
{
    public function __construct(
        #[StateId(CardState::class)]
        public int $id,
        public int $cardNumber,
        public int $cardSecurity,
        public int $cardValidate,
        public string $cardUserName,
        public ?string $cardShortName = '',
        public int $user_id,
        public ?int $user_bank_id = null,
    ) {}

    public function authorize() {}

    public function validate(CardState $card) {}

    public function apply(CardState $card)
    {
        $card->cardNumber = $this->cardNumber;
    }

    public function fired() {}

    public function handle()
    {
        Card::create([
            'id' => $this->id,
            'card_number' => $this->cardNumber,
            'card_security' => $this->cardSecurity,
            'card_validate' => $this->cardValidate,
            'card_userName' => $this->cardUserName,
            'card_shortName' => $this->cardShortName,
            'user_id' => $this->user_id,
            'user_bank_id' => $this->user_bank_id,
        ]);

        // Verbs::unlessReplaying(fn () => Mail::send(new WelcomeEmail($this->user_id)));
    }
}
