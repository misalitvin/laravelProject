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
        $index = 0;

        $lastProduct = $this->productRepository->getLastProduct();

        if (! $lastProduct) {
            return redirect()
                ->route('admin.products.index')
                ->with('success', 'No products to export.');
        }

        $this->productRepository->chunkWithRelations($this->batchSize, function ($products) use (&$index, $lastProduct) {
            $isLast = $products->last()->is($lastProduct);
            ExportProductsJob::dispatch($index, $isLast);
            $index++;
        });

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Export initiated.');
    }
}
