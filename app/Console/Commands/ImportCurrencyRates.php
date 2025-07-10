<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\CurrencyRate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

final class ImportCurrencyRates extends Command
{
    protected $signature = 'currency:import';

    protected $description = 'Import currency exchange rates from ECB';

    public function handle()
    {
        $url = 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';
        $response = Http::get($url);

        if (! $response->ok()) {
            $this->error('Failed to fetch exchange rates.');

            return;
        }

        $xml = simplexml_load_string($response->body());
        $xml->registerXPathNamespace('ns', 'http://www.ecb.int/vocabulary/2002-08-01/eurofxref');

        $rates = $xml->xpath('//ns:Cube/ns:Cube/ns:Cube');

        $currencies = ['USD', 'EUR', 'PLN'];
        CurrencyRate::updateOrCreate(['currency' => 'EUR'], ['rate' => 1]);

        foreach ($rates as $rate) {
            $currency = (string) $rate['currency'];
            $value = (float) $rate['rate'];

            if (in_array($currency, $currencies)) {
                CurrencyRate::updateOrCreate(['currency' => $currency], ['rate' => $value]);
            }
        }

        $this->info('Currency rates imported successfully.');
    }
}
