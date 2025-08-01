<?php

declare(strict_types=1);

namespace App\Interfaces;

interface CurrencyClientInterface
{
    public function fetchRates(): array;
}
