<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Bank;
use App\Models\Brand;
use App\Models\Budget;
use App\Models\Card;
use App\Models\Category;
use App\Models\Goal;
use App\Models\Transaction;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call(BankSeeder::class);
        $this->call(BrandSeeder::class);

        $bank = Bank::query()
            ->enabled()
            ->first();

        $brand = Brand::query()
            ->enabled()
            ->first();

        $account = Account::factory()->create([
            'user_id' => $user->id,
            'bank_id' => $bank->id,
            'name' => 'Conta principal',
            'balance' => 1_000_00,
        ]);

        $card = Card::factory()->create([
            'account_id' => $account->id,
            'name' => 'Cartão principal',
            'limit' => 5_000_00,
            'digits' => '7348',
            'brand_id' => $brand->id,
            'digital' => true,
            'credit' => true,
            'international' => false,
        ]);

        $category = Category::factory()->create([
            'user_id' => $user->id,
            'name' => 'Alimentação',
            'color' => '#22C55E',
        ]);

        $goal = Goal::factory()->create([
            'user_id' => $user->id,
            'name' => 'Honda Civic',
            'total' => 90_000_00,
            'current' => 0,
        ]);

        $budget = Budget::factory()->create([
            'user_id' => $user->id,
            'name' => 'iFood',
            'total' => 300_00,
            'current' => 0,
        ]);

        $transaction = Transaction::factory()->create([
            // 'card_id' => $card->id,
            // 'category_id' => $category->id,
        ]);
    }
}
