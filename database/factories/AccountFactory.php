<?php

namespace Database\Factories;

use App\Models\Bank;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'wallet_id' => Wallet::factory(),
            'bank_id' => Bank::factory(),
            'name' => fake()->name(),
            'balance' => fake()->numberBetween(500_00, 10_000_00),
        ];
    }
}
