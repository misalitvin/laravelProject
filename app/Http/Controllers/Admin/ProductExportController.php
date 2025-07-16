<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\ExportProductsJob;
use App\Models\Product;

final class ProductExportController extends Controller
{
    public function exportToCsv()
    {
        $batchSize = 100;

        $allProducts = Product::with(['manufacturer', 'services'])->get()->toArray();

        $batches = array_chunk($allProducts, $batchSize);

        foreach ($batches as $index => $batch) {
            $isLast = $index === array_key_last($batches);

            ExportProductsJob::dispatch($batch, $index, $isLast);
        }

        return redirect()->route('admin.products.index')->with('success', 'Export initiated.');

    }
}
