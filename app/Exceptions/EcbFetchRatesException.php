<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Throwable;

final class EcbFetchRatesException extends Exception
{
    public function __construct(string $message = 'Failed to fetch exchange rates.', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
