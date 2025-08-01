<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Mail\ProductExportedMail;
use App\Interfaces\Repositories\ProductRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

final class ExportProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $batchIndex;
    public bool $isLastBatch;
    protected string $s3Folder = 'exports';

    public function __construct(int $batchIndex, bool $isLastBatch)
    {
        $this->batchIndex = $batchIndex;
        $this->isLastBatch = $isLastBatch;
    }

    public function handle(ProductRepositoryInterface $productRepository): void
    {
        $products = $productRepository
            ->getBatch($this->batchIndex, 100)
            ->toArray();

        $csvContent = $this->buildCsvContent($products, $this->batchIndex === 0);
        $batchFilename = "{$this->s3Folder}/products_batch_{$this->batchIndex}.csv";

        Storage::disk('s3')->put($batchFilename, $csvContent);

        if ($this->isLastBatch) {
            $this->finalizeExport();
        }
    }

    protected function buildCsvContent(array $products, bool $includeHeader = false): string
    {
        $handle = fopen('php://temp', 'r+');

        if ($includeHeader) {
            fputcsv($handle, [
                'Name', 'Description', 'Release Date', 'Price', 'Manufacturer',
                'Service Name', 'Days to Complete', 'Cost',
            ]);
        }

        foreach ($products as $product) {
            $manufacturerName = $product['manufacturer']['name'] ?? 'N/A';

            if (empty($product['services'])) {
                fputcsv($handle, [
                    $product['name'],
                    $product['description'],
                    $product['release_date'],
                    $product['price'],
                    $manufacturerName,
                    '', '', '',
                ]);
            } else {
                foreach ($product['services'] as $service) {
                    $daysToComplete = $service['pivot']['days_to_complete'] ?? '';
                    $cost = $service['pivot']['cost'] ?? '';

                    fputcsv($handle, [
                        $product['name'],
                        $product['description'],
                        $product['release_date'],
                        $product['price'],
                        $manufacturerName,
                        $service['name'],
                        $daysToComplete,
                        $cost,
                    ]);
                }
            }
        }

        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        return $csvContent;
    }

    protected function finalizeExport(): void
    {
        $files = Storage::disk('s3')->files($this->s3Folder);

        $batchFiles = array_filter($files, fn ($file) =>
        str_starts_with($file, "{$this->s3Folder}/products_batch_")
        );

        $finalCsvContent = $this->mergeBatchFiles($batchFiles);

        $finalFilename = "{$this->s3Folder}/products.csv";
        Storage::disk('s3')->put($finalFilename, $finalCsvContent);

        Mail::to(config('mail.admin_address'))->send(
            new ProductExportedMail($finalFilename)
        );
    }

    protected function mergeBatchFiles(array $batchFiles): string
    {
        $handle = fopen('php://temp', 'r+');
        $isFirstFile = true;

        foreach ($batchFiles as $file) {
            $content = Storage::disk('s3')->get($file);
            $lines = explode("\n", trim($content));

            if (! $isFirstFile) {
                array_shift($lines);
            }

            foreach ($lines as $line) {
                if (trim($line) !== '') {
                    fwrite($handle, $line."\n");
                }
            }

            $isFirstFile = false;
        }

        rewind($handle);
        $mergedContent = stream_get_contents($handle);
        fclose($handle);

        return $mergedContent;
    }
}
