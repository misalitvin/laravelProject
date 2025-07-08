<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ProductExportedMail;
use App\Models\Product;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class ProductExportController extends Controller
{
    public function __invoke(): StreamedResponse
    {
        $filename = 'product_catalog_'.now()->format('Y_m_d_His').'.csv';
        $localPath = storage_path("app/exports/{$filename}");

        if (! is_dir(dirname($localPath))) {
            mkdir(dirname($localPath), 0755, true);
        }

        $handle = fopen($localPath, 'w');
        fputcsv($handle, [
            'Name', 'Description', 'Release Date', 'Price', 'Manufacturer',
            'Service Name', 'Days to Complete', 'Cost',
        ]);

        Product::with(['manufacturer', 'services'])->chunk(100, function ($products) use ($handle) {
            foreach ($products as $product) {
                if ($product->services->isEmpty()) {
                    fputcsv($handle, [
                        $product->name,
                        $product->description,
                        $product->release_date,
                        $product->price,
                        $product->manufacturer->name ?? 'N/A',
                        '', '', '',
                    ]);
                } else {
                    foreach ($product->services as $service) {
                        fputcsv($handle, [
                            $product->name,
                            $product->description,
                            $product->release_date,
                            $product->price,
                            $product->manufacturer->name ?? 'N/A',
                            $service->name,
                            $service->pivot->days_to_complete,
                            $service->pivot->cost,
                        ]);
                    }
                }
            }
        });

        fclose($handle);

        $s3Path = "exports/{$filename}";
        Storage::disk('s3')->put($s3Path, file_get_contents($localPath));

        Mail::to(config('mail.admin_address'))->send(new ProductExportedMail($s3Path));

        return response()->streamDownload(fn () => readfile($localPath), $filename);
    }
}
