<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\ProductFilterData;
use App\Interfaces\Repositories\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class ProductService
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function getFilteredProducts(ProductFilterData $filterData): LengthAwarePaginator
    {
        return $this->productRepository->filterProducts($filterData);
    }

    public function syncServices(Product $product, array $services = []): void
    {
        $this->productRepository->syncServices($product, $services);
    }
}
