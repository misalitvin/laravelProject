<?php

declare(strict_types=1);

namespace App\Interfaces\Repositories;

use App\DTOs\ProductFilterData;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ProductRepositoryInterface
{
    public function getLastProduct(): ?Product;

    public function chunkWithRelations(int $batchSize, callable $callback): void;

    public function getBatch(int $batchIndex, int $batchSize): Collection;

    public function filterProducts(ProductFilterData $filterData): LengthAwarePaginator;

    public function create(array $data): Product;

    public function findById(int $id): Product;

    public function update(Product $product, array $data): bool;

    public function delete(Product $product): bool;

    public function findWithRelations(int $id, array $relations = []): Product;

    public function syncServices(Product $product, array $services): void;
}
