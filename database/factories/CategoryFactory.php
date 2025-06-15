<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
final class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement(['Alimentação', 'Saúde', 'Transporte', 'Entretenimento']),
            'color' => fake()->hexColor(),
            'type' => fake()->randomElement(['IN', 'OUT']),
        ];
    }

    public function forWallet(Wallet $wallet): static
    {
        return $this->state(fn (array $attributes) => [
            'wallet_id' => $wallet->id,
        ]);
    }
}
