<?php

declare(strict_types=1);

namespace App\Providers;

use App\Console\Commands\EnsureS3BucketExists;
use Aws\S3\S3Client;
use Illuminate\Support\ServiceProvider;

class S3ServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(S3Client::class, function () {
            $s3Config = config('filesystems.disks.s3');

            return new S3Client([
                'version' => 'latest',
                'region' => $s3Config['region'],
                'endpoint' => $s3Config['endpoint'] ?? null,
                'use_path_style_endpoint' => $s3Config['use_path_style_endpoint'] ?? false,
                'credentials' => [
                    'key' => $s3Config['key'],
                    'secret' => $s3Config['secret'],
                ],
            ]);
        });

        $this->app->singleton('s3.bucket', fn () => config('filesystems.disks.s3.bucket'));

        $this->app->singleton(EnsureS3BucketExists::class, function ($app) {
            return new EnsureS3BucketExists(
                $app->make(S3Client::class),
                $app->make('s3.bucket')
            );
        });
    }
}
