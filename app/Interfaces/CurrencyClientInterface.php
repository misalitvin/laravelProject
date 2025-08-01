<?php
namespace App\Interfaces;

interface CurrencyClientInterface
{
    public function fetchRates(): array;
}

