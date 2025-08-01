<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use Illuminate\Console\Command;

class EnsureS3BucketExists extends Command
{
    protected $signature = 's3:ensure-bucket';

    protected $description = 'Ensure that the configured S3 bucket exists';

    public function handle(): void
    {
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

        try {
            $bucket = $s3Config['bucket'];

            if (! $client->doesBucketExist($bucket)) {
                $client->createBucket(['Bucket' => $bucket]);
                $client->waitUntil('BucketExists', ['Bucket' => $bucket]);
                $this->info("Bucket '{$bucket}' created.");
            } else {
                $this->info("Bucket '{$bucket}' already exists.");
            }

        } catch (AwsException $e) {
            $this->error('Failed to check or create bucket: '.$e->getMessage());
        }
    }
}
