<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            [
                'identifier' => 'alipay',
                'name' => 'Alipay',
                'enabled' => false,
            ],
            [
                'identifier' => 'amex',
                'name' => 'Amex',
                'enabled' => true,
            ],
            [
                'identifier' => 'diners',
                'name' => 'Diners',
                'enabled' => false,
            ],
            [
                'identifier' => 'discover',
                'name' => 'Discover',
                'enabled' => false,
            ],
            [
                'identifier' => 'elo',
                'name' => 'Elo',
                'enabled' => true,
            ],
            [
                'identifier' => 'hiper',
                'name' => 'Hiper',
                'enabled' => true,
            ],
            [
                'identifier' => 'hipercard',
                'name' => 'Hipercard',
                'enabled' => true,
            ],
            [
                'identifier' => 'jcb',
                'name' => 'jcb',
                'enabled' => false,
            ],
            [
                'identifier' => 'maestro',
                'name' => 'maestro',
                'enabled' => false,
            ],
            [
                'identifier' => 'mastercard',
                'name' => 'Mastercard',
                'enabled' => true,
            ],
            [
                'identifier' => 'mir',
                'name' => 'mir',
                'enabled' => false,
            ],
            [
                'identifier' => 'paypal',
                'name' => 'Paypal',
                'enabled' => true,
            ],
            [
                'identifier' => 'unionpay',
                'name' => 'unionpay',
                'enabled' => false,
            ],
            [
                'identifier' => 'visa',
                'name' => 'Visa',
                'enabled' => true,
            ],
        ];

        foreach ($brands as $brand) {
            Brand::create([
                'identifier' => $brand['identifier'],
                'name' => $brand['name'],
                'enabled' => $brand['enabled'],
            ]);
        }
    }
}
