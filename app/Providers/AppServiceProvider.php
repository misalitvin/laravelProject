<?php

declare(strict_types=1);

namespace App\Providers;

use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (app()->isLocal()) {
            $s3Config = config('filesystems.disks.s3');

            $client = new S3Client([
                'version' => 'latest',
                'region' => $s3Config['region'],
                'endpoint' => $s3Config['endpoint'],
                'use_path_style_endpoint' => $s3Config['use_path_style_endpoint'],
                'credentials' => [
                    'key' => $s3Config['key'],
                    'secret' => $s3Config['secret'],
                ],
            ]);

            $bucket = $s3Config['bucket'];

            try {
                if (! $client->doesBucketExist($bucket)) {
                    $client->createBucket([
                        'Bucket' => $bucket,
                    ]);
                    $client->waitUntil('BucketExists', ['Bucket' => $bucket]);
                }
            } catch (AwsException $e) {
                logger()->error('S3 bucket creation failed: '.$e->getMessage());
            }
        }
    }
}
