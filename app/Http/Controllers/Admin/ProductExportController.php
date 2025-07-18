<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ExportProductsJob;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;

final class ProductExportController extends Controller
{
    public function exportToCsv(): RedirectResponse
    {
        $batchSize = 100;
        $index = 0;

        $lastProduct = Product::latest('id')->first();

        if (! $lastProduct) {
            return redirect()->route('admin.products.index')->with('success', 'No products to export.');
        }

        Product::with(['manufacturer', 'services'])
            ->orderBy('id')
            ->chunk($batchSize, function ($products) use (&$index, $lastProduct) {
                $isLast = $products->last()->is($lastProduct);

                ExportProductsJob::dispatch($products->toArray(), $index, $isLast);
                $index++;
            });

        return redirect()->route('admin.products.index')->with('success', 'Export initiated.');
    }
}
