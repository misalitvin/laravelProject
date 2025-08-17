<?php

declare(strict_types=1);

namespace App\Jobs;

use App\DTOs\ProductDTO;
use App\DTOs\ServiceDTO;
use App\Interfaces\Repositories\ProductRepositoryInterface;
use App\Mail\ProductExportedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Mail\Mailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

final class ExportProductsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $batchIndex;

    public bool $isLastBatch;

    protected string $s3Folder = 'exports';

    protected Filesystem $storage;

    protected Mailer $mailer;

    public function __construct(int $batchIndex, bool $isLastBatch)
    {
        $this->batchIndex = $batchIndex;
        $this->isLastBatch = $isLastBatch;
    }

    public function handle(
        ProductRepositoryInterface $productRepository,
        Filesystem $storage,
        Mailer $mailer
    ): void {
        $this->storage = $storage;
        $this->mailer = $mailer;

        /** @var ProductDTO[] $products */
        $products = $productRepository
            ->getBatch($this->batchIndex, 100);

        $csvContent = $this->buildCsvContent($products, $this->batchIndex === 0);
        $batchFilename = "{$this->s3Folder}/products_batch_{$this->batchIndex}.csv";

        $this->storage->put($batchFilename, $csvContent);

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
            $manufacturerName = $product->manufacturerName ?? 'N/A';

            if (empty($product->services)) {
                fputcsv($handle, [
                    $product->name,
                    $product->description,
                    $product->releaseDate,
                    $product->price,
                    $manufacturerName,
                    '', '', '',
                ]);
            } else {
                /** @var ServiceDTO $service */
                foreach ($product->services as $service) {
                    fputcsv($handle, [
                        $product->name,
                        $product->description,
                        $product->releaseDate,
                        $product->price,
                        $manufacturerName,
                        $service->name,
                        $service->daysToComplete,
                        $service->cost,
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
        $files = $this->storage->files($this->s3Folder);

        $batchFiles = array_filter(
            $files,
            fn ($file) => str_starts_with($file, "{$this->s3Folder}/products_batch_")
        );

        $finalCsvContent = $this->mergeBatchFiles($batchFiles);

        $finalFilename = "{$this->s3Folder}/products.csv";
        $this->storage->put($finalFilename, $finalCsvContent);

        $this->mailer->to(config('mail.admin_address'))->send(
            new ProductExportedMail($finalFilename)
        );
    }

    protected function mergeBatchFiles(array $batchFiles): string
    {
        $handle = fopen('php://temp', 'r+');
        $isFirstFile = true;

        foreach ($batchFiles as $file) {
            $content = $this->storage->get($file);
            $lines = explode("\n", mb_trim($content));

            if (! $isFirstFile) {
                array_shift($lines);
            }

            foreach ($lines as $line) {
                if (mb_trim($line) !== '') {
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
