<?php

namespace Database\Seeders;

use App\Events\AccountCreated;
use App\Events\TransactionInCreated;
use App\Events\TransactionOutCreated;
use App\Events\WalletCreated;
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
        $this->call(BankSeeder::class);
        $this->call(BrandSeeder::class);

        $user = User::factory()->create([
            'name' => 'Teste',
            'email' => 'teste@jagastei.com.br',
        ]);

        $personalWallet = WalletCreated::fire(
            user_id: $user->id,
            name: 'Carteira pessoal',
        );

        WalletCreated::fire(
            user_id: $user->id,
            name: 'Carteira da empresa',
        );

        $user->update([
            'current_wallet_id' => $personalWallet->id,
        ]);

        $bank = Bank::query()
            ->enabled()
            ->first();

        $brand = Brand::query()
            ->enabled()
            ->first();

        $account = AccountCreated::fire(
            wallet_id: $personalWallet->id,
            bank_id: $bank->id,
            name: 'Conta principal',
            balance: 1_000_00,
        );

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
            ->state([
                'account_id' => $account->id,
            ])
            ->for($brand)
            ->create();

        $categories = Category::factory()
            ->count(5)
            ->state([
                'wallet_id' => $personalWallet->id,
            ])
            ->create();

        Goal::factory()
            ->count(5)
            ->state([
                'wallet_id' => $personalWallet->id,
            ])
            ->create();

        Budget::factory()
            ->count(5)
            ->state([
                'wallet_id' => $personalWallet->id,
            ])
            ->create();

        // dump($account->id);

        Transaction::factory()
            ->count(300)
            ->recycle($categories)
            ->afterMaking(function (Transaction $transaction) {
                $category = $transaction->category;
                $transaction->type = $category->type;
            })
            ->state([
                'account_id' => $account->id,
            ])
            ->for($card)
            ->create()
            ->each(function ($transaction) {
                if ($transaction->type === 'IN') {
                    TransactionInCreated::fire(
                        title: $transaction->title,
                        value: $transaction->value,
                        account_id: $transaction->account_id,
                        category_id: $transaction->category_id,
                        created_at: $transaction->created_at,
                    );
                } else {
                    TransactionOutCreated::fire(
                        title: $transaction->title,
                        value: $transaction->value,
                        account_id: $transaction->account_id,
                        category_id: $transaction->category_id,
                        created_at: $transaction->created_at,
                    );
                }

                $transaction->forceDelete();
            });
    }
}
