<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\Repositories\ProductRepositoryInterface;
use App\Jobs\ExportProductsJob;
use Illuminate\Http\RedirectResponse;

final class ProductExportController extends Controller
{
    private int $batchSize = 100;

    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) {}

    public function exportToCsv(): RedirectResponse
    {
        $lastProduct = $this->productRepository->getLastProduct();

        if (! $lastProduct) {
            return redirect()
                ->route('admin.products.index')
                ->with('success', 'No products to export.');
        }

        $batchIndex = 0;
        do {
            $products = $this->productRepository->getBatch($batchIndex, $this->batchSize);

            if ($products->isEmpty()) {
                break;
            }

            $isLast = $products->contains($lastProduct);

            ExportProductsJob::dispatch($batchIndex, $isLast);

            $batchIndex++;
        } while (! $isLast);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Export initiated.');
    }
}
