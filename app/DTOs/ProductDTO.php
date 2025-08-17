<?php

declare(strict_types=1);

namespace App\DTOs;

use DateTimeImmutable;

final class ProductDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $description,
        public DateTimeImmutable $releaseDate,
        public float $price,
        public ?ManufacturerDTO $manufacturer,
        public array $services
    ) {}
}
