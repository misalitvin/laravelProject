<?php
namespace App\Repositories;

use App\Interfaces\Repositories\CurrencyRateRepositoryInterface;
use App\Models\CurrencyRate;
use Illuminate\Support\Collection;

final class CurrencyRateRepository implements CurrencyRateRepositoryInterface
{
    public function save(string $currency, float $rate): void
    {
        CurrencyRate::updateOrCreate(
            ['currency' => $currency],
            ['rate' => $rate]
        );
    }

    public function getRatesForCurrencies(array $currencies): Collection
    {
        return CurrencyRate::whereIn('currency', $currencies)
            ->pluck('rate', 'currency');
    }
}

