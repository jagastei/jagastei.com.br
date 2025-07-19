<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Events\AccountCreated;
use App\Events\TransactionInCreated;
use App\Events\TransactionOutCreated;
use App\Events\WalletCreated;
use App\Models\Account;
use App\Models\Bank;
use App\Models\Brand;
use App\Models\Budget;
use App\Models\Card;
use App\Models\Category;
use App\Models\Goal;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
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

        $personalWalletCreated = WalletCreated::fire(
            user_id: $user->id,
            name: 'Carteira pessoal',
        );

        WalletCreated::fire(
            user_id: $user->id,
            name: 'Carteira da empresa',
        );

        $user->update([
            'current_wallet_id' => $personalWalletCreated->wallet_id,
        ]);

        $bank = Bank::query()
            ->enabled()
            ->first();

        $brand = Brand::query()
            ->enabled()
            ->first();

        $accountCreated = AccountCreated::fire(
            wallet_id: $personalWalletCreated->wallet_id,
            bank_id: $bank->id,
            name: 'Conta principal',
            balance: 1_000_00,
        );

        Card::factory()->create([
            'account_id' => $accountCreated->account_id,
            'name' => 'Cartão principal',
            'limit' => 5_000_00,
            'digits' => '7348',
            'brand_id' => $brand->id,
            'credit' => true,
            'virtual' => true,
            'international' => false,
        ]);

        $categories = Category::factory()
            ->count(5)
            ->state([
                'wallet_id' => $personalWalletCreated->wallet_id,
            ])
            ->create();

        $accounts = Account::factory()
            ->count(5)
            ->state([
                'wallet_id' => $personalWalletCreated->wallet_id,
            ])
            ->make()
            ->each(function ($account) {
                $account->bank_id = Bank::enabled()->inRandomOrder()->first()->id;
            });

        foreach ($accounts as $account) {
            $accountCreated = AccountCreated::fire(
                wallet_id: $personalWalletCreated->wallet_id,
                bank_id: $account->bank_id,
                name: $account->name,
                balance: $account->balance,
            );

            Card::factory()
                ->count(1)
                ->state([
                    'account_id' => $accountCreated->account_id,
                ])
                ->for(Brand::enabled()->inRandomOrder()->first())
                ->create();

            // Generate transactions for account
            $quantity = fake()->numberBetween(50, 100);

            for ($i = 0; $i < $quantity; $i++) {
                $date = fake()->dateTimeBetween(startDate: '-1 month', endDate: '+1 month');

                $account = $accounts->random();
                $category = $categories->random();

                if ($category->type === 'IN') {
                    TransactionInCreated::fire(
                        title: fake()->name(),
                        value: fake()->numberBetween(50_00, 300_00),
                        account_id: $accountCreated->account_id,
                        category_id: $category->id->id(),
                        datetime: $date,
                    );
                } else {
                    TransactionOutCreated::fire(
                        title: fake()->name(),
                        value: fake()->numberBetween(50_00, 300_00),
                        account_id: $accountCreated->account_id,
                        category_id: $category->id->id(),
                        datetime: $date,
                    );
                }
            }
        }

        Goal::factory()
            ->count(5)
            ->state([
                'wallet_id' => $personalWalletCreated->wallet_id,
            ])
            ->create();

        Budget::factory()
            ->count(5)
            ->state([
                'wallet_id' => $personalWalletCreated->wallet_id,
            ])
            ->create();
    }
}
