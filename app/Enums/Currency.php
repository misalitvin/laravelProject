<?php

declare(strict_types=1);

namespace App\Enums;

enum Currency: string
{
    case USD = 'USD';
    case EUR = 'EUR';
    case PLN = 'PLN';

    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }

}
