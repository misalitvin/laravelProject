<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Aws\Exception\AwsException;
use Aws\S3\S3Client;
use Illuminate\Console\Command;

final class EnsureS3BucketExists extends Command
{
    protected $signature = 's3:ensure-bucket';

    protected $description = 'Ensure that the configured S3 bucket exists';

    private S3Client $client;

    private string $bucket;

    public function __construct(S3Client $client, string $bucket)
    {
        parent::__construct();
        $this->client = $client;
        $this->bucket = $bucket;
    }

    public function handle(): void
    {
        try {
            if (! $this->client->doesBucketExist($this->bucket)) {
                $this->client->createBucket(['Bucket' => $this->bucket]);
                $this->client->waitUntil('BucketExists', ['Bucket' => $this->bucket]);
                $this->info("Bucket '{$this->bucket}' created.");
            } else {
                $this->info("Bucket '{$this->bucket}' already exists.");
            }
        } catch (AwsException $e) {
            $this->error('Failed to check or create bucket: '.$e->getMessage());
        }
    }
}
