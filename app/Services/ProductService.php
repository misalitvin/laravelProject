<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

final class ProductService
{
    public function searchAndFilter(Request $request): Builder
    {
        $query = Product::query();

        if ($search = $request->input('search')) {
            $search = mb_strtolower($search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(description) LIKE ?', ["%{$search}%"]);
            });
        }

        if ($priceMin = $request->input('price_min')) {
            $query->where('price', '>=', $priceMin);
        }

        if ($priceMax = $request->input('price_max')) {
            $query->where('price', '<=', $priceMax);
        }

        if ($sort = $request->input('sort')) {
            $query->orderBy(match ($sort) {
                'name_asc' => 'name',
                'name_desc' => ['name', 'desc'],
                default => 'created_at',
            });
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query;
    }

    public function syncServices(Product $product, array $services = []): void
    {
        $syncData = [];

        foreach ($services as $serviceId => $serviceData) {
            if (! empty($serviceData['selected'])) {
                $syncData[$serviceId] = [
                    'days_to_complete' => $serviceData['days_to_complete'] ?? 0,
                    'cost' => $serviceData['cost'] ?? 0,
                ];
            }
        }

        $product->services()->sync($syncData);
    }
}
