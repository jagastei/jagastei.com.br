<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Card>
 */
final class CardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_id' => Account::factory(),
            'name' => fake()->name(),
            'limit' => fake()->numberBetween(100_00, 10_000_00),
            'digits' => fake()->creditCardNumber(),
            'expiration_date' => fake()->dateTimeBetween(startDate: '-1 month', endDate: '+5 years')->format('Y-m-d'),
            'credit' => fake()->boolean(),
            'virtual' => fake()->boolean(),
            'international' => fake()->boolean(),
        ];
    }
}
