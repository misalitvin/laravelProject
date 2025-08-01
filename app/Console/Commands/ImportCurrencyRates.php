<?php

namespace App\Console\Commands;

use App\Enums\Currency;
use Illuminate\Console\Command;
use App\Interfaces\CurrencyClientInterface;
use App\Interfaces\Repositories\CurrencyRateRepositoryInterface;

final class ImportCurrencyRates extends Command
{
    protected $signature = 'currency:import';
    protected $description = 'Import currency exchange rates from ECB';

    public function __construct(
        private CurrencyClientInterface $currencyClient,
        private CurrencyRateRepositoryInterface $currencyRateRepository
    ) {
        parent::__construct();
    }

    public function handle(): void
    {
        try {
            $rates = $this->currencyClient->fetchRates();

            foreach (Currency::all() as $currency) {
                if (isset($rates[$currency])) {
                    $this->currencyRateRepository->save($currency, $rates[$currency]);
                }
            }

            $this->info('Currency rates imported successfully.');
        } catch (\Throwable $e) {
            $this->error('Failed to import currency rates: ' . $e->getMessage());
        }
    }
}
