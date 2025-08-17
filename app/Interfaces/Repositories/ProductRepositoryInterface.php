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

    public function getBatch(int $batchIndex, int $batchSize): Collection;

    public function filterProducts(ProductFilterData $filterData): LengthAwarePaginator;

    public function create(array $data): Product;

    public function findById(int $id): Product;

    public function update(Product $product, array $data): bool;

    public function delete(Product $product): bool;

    public function findByIdWithServices(int $id): Product;

    public function findByIdWithAllRelations(int $id): Product;

    public function syncServices(Product $product, array $services): void;
}
