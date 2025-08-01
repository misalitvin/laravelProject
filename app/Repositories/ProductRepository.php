<?php


namespace App\Repositories;

use App\DTOs\ProductFilterData;
use App\Interfaces\Repositories\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    public function findWithRelations(int $id, array $relations = []): Product
    {
        return Product::with($relations)->findOrFail($id);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): bool
    {
        return $product->update($data);
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }

    public function getAll(): Collection
    {
        return Product::with(['services', 'manufacturer'])->get();
    }

    public function findById(int $id): Product
    {
        return Product::findOrFail($id);
    }


    public function getLastProduct(): ?Product
    {
        return Product::latest('id')->first();
    }

    public function chunkWithRelations(int $batchSize, callable $callback): void
    {
        Product::with(['manufacturer', 'services'])
            ->orderBy('id')
            ->chunk($batchSize, $callback);
    }

    public function getBatch(int $batchIndex, int $batchSize): Collection
    {
        return Product::with(['manufacturer', 'services'])
            ->orderBy('id')
            ->skip($batchIndex * $batchSize)
            ->take($batchSize)
            ->get();
    }

    public function filterProducts(ProductFilterData $filterData): LengthAwarePaginator
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

        return $query->paginate(10);
    }

    public function syncServices(Product $product, array $services): void
    {
        $syncData = [];

        foreach ($services as $serviceId => $serviceData) {
            if (!empty($serviceData['selected'])) {
                $syncData[$serviceId] = [
                    'days_to_complete' => $serviceData['days_to_complete'] ?? null,
                    'cost' => $serviceData['cost'] ?? null,
                ];
            }
        }

        $product->services()->sync($syncData);
    }
}
