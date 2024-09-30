<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = fake()->dateTimeBetween(startDate: '-2 month', endDate: '+1 month');

        return [
            'title' => fake()->name(),
            'type' => fake()->randomElement(['IN', 'OUT']),
            'value' => fake()->numberBetween(50_00, 300_00),
            'category_id' => Category::factory(),
            'method' => fake()->randomElement(['CARD', 'TED', 'PIX']),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
