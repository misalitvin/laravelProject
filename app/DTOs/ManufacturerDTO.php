<?php

declare(strict_types=1);

namespace App\DTOs;

final class ManufacturerDTO
{
    public function __construct(
        public string $name
    ) {}
}
