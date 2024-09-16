<?php

namespace Database\Seeders;

use App\Models\Bank;
use GuzzleHttp\Client;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use JsonException;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try  {
            $banks = json_decode(file_get_contents(database_path('seeders/fixtures/banks.json')), true);

            foreach($banks as $bank) {
                Bank::create([
                    'code' => $bank['COMPE'],
                    'short_name' => $bank['ShortName'],
                    'long_name' => $bank['LongName'],
                ]);
            }
        } catch (JsonException $e) {
            dd($e->getMessage());
        }
        
    }
}
