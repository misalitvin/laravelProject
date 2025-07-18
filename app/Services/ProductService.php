<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\ProductFilterData;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

final class ProductService
{
    public function searchAndFilter(ProductFilterData $filterData): Builder
    {
        $query = Product::query();

        if ($search = $filterData->search) {
            $search = mb_strtolower($search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(description) LIKE ?', ["%{$search}%"]);
            });
        }

        if ($filterData->manufacturerId !== null) {
            $query->where('manufacturer_id', $filterData->manufacturerId);
        }

        if ($filterData->serviceId !== null) {
            $query->whereHas('services', function ($q) use ($filterData) {
                $q->where('services.id', $filterData->serviceId);
            });
        }

        if ($filterData->minPrice !== null) {
            $query->where('price', '>=', $filterData->minPrice);
        }

        if ($filterData->maxPrice !== null) {
            $query->where('price', '<=', $filterData->maxPrice);
        }

        if ($sort = $filterData->sort) {
            $query->orderBy(
                ...match ($sort) {
                'name_asc' => ['name', 'asc'],
                'name_desc' => ['name', 'desc'],
                default => ['created_at', 'desc'],
            }
            );
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }

    public function syncServices(Product $product, array $services = []): void
    {
        $syncData = [];

        foreach ($services as $serviceId => $serviceData) {
            if (isset($serviceData['selected']) && $serviceData['selected']) {
                $syncData[$serviceId] = [
                    'days_to_complete' => $serviceData['days_to_complete'] ?? null,
                    'cost' => $serviceData['cost'] ?? null,
                ];
            }
        }

        $product->services()->sync($syncData);
    }
}
