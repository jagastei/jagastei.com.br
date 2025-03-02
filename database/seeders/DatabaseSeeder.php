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
use App\Models\Wallet;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(BankSeeder::class);
        $this->call(BrandSeeder::class);

        $user = User::factory()->create([
            'name' => 'Teste',
            'email' => 'teste@jagastei.com.br',
        ]);

        $personalWallet = Wallet::factory()->forUser($user)->create([
            'name' => 'Carteira pessoal',
        ]);

        Wallet::factory()->forUser($user)->create([
            'name' => 'Carteira da empresa',
        ]);

        $user->update([
            'current_wallet_id' => $personalWallet->id,
        ]);

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

        Account::factory()
            ->count(5)
            ->for($user)
            ->for($bank)
            ->create();

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

        Card::factory()
            ->count(5)
            ->for($account)
            ->for($brand)
            ->create();

        // $category = Category::factory()->create([
        //     'user_id' => $user->id,
        //     'name' => 'Alimentação',
        //     'color' => '#22C55E',
        // ]);

        $categories = Category::factory()
            ->count(5)
            ->for($user)
            ->create();

        $goal = Goal::factory()->create([
            'user_id' => $user->id,
            'name' => 'Honda Civic',
            'total' => 90_000_00,
            'current' => 0,
        ]);

        Goal::factory()
            ->count(5)
            ->for($user)
            ->create();

        $budget = Budget::factory()->create([
            'user_id' => $user->id,
            'name' => 'iFood',
            'total' => 300_00,
            'current' => 0,
        ]);

        Budget::factory()
            ->count(5)
            ->for($user)
            ->create();

        Transaction::factory()
            ->count(300)
            ->recycle($categories)
            ->for($account)
            ->for($card)
            ->create();
    }
}
