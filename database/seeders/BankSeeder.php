<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;
use JsonException;

final class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            $banks = json_decode(file_get_contents(database_path('seeders/fixtures/banks.json')), true);

            $enabled = [
                [
                    'COMPE' => '001',
                    'ShortName' => 'BCO DO BRASIL S.A.',
                    'LongName' => 'Banco do Brasil S.A.',
                ],
                [
                    'COMPE' => '033',
                    'ShortName' => 'BCO SANTANDER (BRASIL) S.A.',
                    'LongName' => 'BANCO SANTANDER (BRASIL) S.A.',
                ],
                [
                    'COMPE' => '077',
                    'ShortName' => 'BANCO INTER',
                    'LongName' => 'Banco Inter S.A.',
                ],
                [
                    'COMPE' => '104',
                    'ShortName' => 'CAIXA ECONOMICA FEDERAL',
                    'LongName' => 'Caixa Econômica Federal',
                ],
                [
                    'COMPE' => '208',
                    'ShortName' => 'BANCO BTG PACTUAL S.A.',
                    'LongName' => 'Banco BTG Pactual S.A.',
                ],
                [
                    'COMPE' => '237',
                    'ShortName' => 'BCO BRADESCO S.A.',
                    'LongName' => 'Banco Bradesco S.A.',
                ],
                [
                    'COMPE' => '260',
                    'ShortName' => 'NU PAGAMENTOS - IP',
                    'LongName' => 'NU PAGAMENTOS S.A. - INSTITUIÇÃO DE PAGAMENTO',
                ],
                [
                    'COMPE' => '290',
                    'ShortName' => 'PAGSEGURO INTERNET IP S.A.',
                    'LongName' => 'PAGSEGURO INTERNET INSTITUIÇÃO DE PAGAMENTO S.A.',
                ],
                [
                    'COMPE' => '323',
                    'ShortName' => 'MERCADO PAGO IP LTDA.',
                    'LongName' => 'MERCADO PAGO INSTITUIÇÃO DE PAGAMENTO LTDA.',
                ],
                [
                    'COMPE' => '336',
                    'ShortName' => 'BCO C6 S.A.',
                    'LongName' => 'Banco C6 S.A.',
                ],
                [
                    'COMPE' => '341',
                    'ShortName' => 'ITAÚ UNIBANCO S.A.',
                    'LongName' => 'ITAÚ UNIBANCO S.A.',
                ],
                [
                    'COMPE' => '422',
                    'ShortName' => 'BCO SAFRA S.A.',
                    'LongName' => 'Banco Safra S.A.42',
                ],
                [
                    'COMPE' => '536',
                    'ShortName' => 'NEON PAGAMENTOS S.A. IP',
                    'LongName' => 'NEON PAGAMENTOS S.A. - INSTITUIÇÃO DE PAGAMENTO',
                ],
                [
                    'COMPE' => '655',
                    'ShortName' => 'BCO VOTORANTIM S.A.',
                    'LongName' => 'Banco Votorantim S.A.',
                ],
                [
                    'COMPE' => '756',
                    'ShortName' => 'BANCO SICOOB S.A.',
                    'LongName' => 'BANCO COOPERATIVO SICOOB S.A. - BANCO SICOOB',
                ],
            ];

            foreach ($banks as $bank) {
                Bank::create([
                    'code' => $bank['COMPE'],
                    'short_name' => $bank['ShortName'],
                    'long_name' => $bank['LongName'],
                ]);
            }

            foreach ($enabled as $bank) {
                Bank::query()
                    ->where('code', $bank['COMPE'])
                    ->update([
                        'enabled' => true,
                    ]);
            }
        } catch (JsonException $e) {
            echo $e->getMessage();
        }
    }
}
