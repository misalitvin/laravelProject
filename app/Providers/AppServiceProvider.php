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
        $s3Config = config('filesystems.disks.s3');

        // Create AWS S3 client using config values
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
            // Check if bucket exists
            if (! $client->doesBucketExist($bucket)) {
                // Create the bucket if it doesn't exist
                $client->createBucket([
                    'Bucket' => $bucket,
                ]);
                // Optionally wait until the bucket exists
                $client->waitUntil('BucketExists', ['Bucket' => $bucket]);
            }
        } catch (AwsException $e) {
            // Log the error but don't stop application booting
            logger()->error('S3 bucket creation failed: '.$e->getMessage());
        }
    }
}
