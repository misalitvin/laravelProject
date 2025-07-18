<?php

declare(strict_types=1);

namespace App\DTOs;

use Illuminate\Http\Request;

final class ProductFilterData
{
    public ?string $search;
    public ?int $manufacturerId;
    public ?int $serviceId;
    public ?float $minPrice;
    public ?float $maxPrice;
    public ?string $sort;

    public function __construct(
        ?string $search,
        ?int $manufacturerId,
        ?int $serviceId,
        ?float $minPrice,
        ?float $maxPrice,
        ?string $sort
    ) {
        $this->search = $search;
        $this->manufacturerId = $manufacturerId;
        $this->serviceId = $serviceId;
        $this->minPrice = $minPrice;
        $this->maxPrice = $maxPrice;
        $this->sort = $sort;
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('search'),
            $request->input('manufacturer_id') ? (int) $request->input('manufacturer_id') : null,
            $request->input('service_id') ? (int) $request->input('service_id') : null,
            $request->input('price_min') ? (float) $request->input('price_min') : null,
            $request->input('price_max') ? (float) $request->input('price_max') : null,
            $request->input('sort')
        );
    }


}
