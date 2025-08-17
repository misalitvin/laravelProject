<?php

declare(strict_types=1);

namespace App\Interfaces;

interface CurrencyClientInterface
{
    /** @return array<string, float> */
    public function fetchRates(): array;
}
