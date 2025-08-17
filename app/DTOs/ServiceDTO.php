<?php

declare(strict_types=1);

namespace App\DTOs;

final class ServiceDTO
{
    public function __construct(
        public string $name,
        public ?int $daysToComplete = null,
        public ?float $cost = null
    ) {}
}
