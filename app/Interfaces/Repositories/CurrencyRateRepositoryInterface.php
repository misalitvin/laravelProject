<?php

declare(strict_types=1);

namespace App\Interfaces\Repositories;

use Illuminate\Support\Collection;

interface CurrencyRateRepositoryInterface
{
    public function save(string $currency, float $rate): void;

    public function getRatesForCurrencies(array $currencies): Collection;
}
