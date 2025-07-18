<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Jobs\ExportProductsJob;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

final class ProductExportControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->actingAs($admin);
    }

    public function test_it_redirects_with_success_if_no_products_exist(): void
    {
        $this->get(route('admin.products.export'))
            ->assertRedirect(route('admin.products.index'))
            ->assertSessionHas('success', 'No products to export.');
    }

    public function test_it_dispatches_export_jobs_and_redirects(): void
    {
        Queue::fake();

        $service = Service::factory()->create(['name' => 'Warranty Service']);

        Product::factory()
            ->count(250)
            ->hasAttached($service, [
                'days_to_complete' => 5,
                'cost' => 100.0,
            ])
            ->create();

        $this->get(route('admin.products.export'))
            ->assertRedirect(route('admin.products.index'))
            ->assertSessionHas('success', 'Export initiated.');

        Queue::assertPushed(ExportProductsJob::class, 3);

        Queue::assertPushed(ExportProductsJob::class, function (ExportProductsJob $job) {
            return $job->batchIndex === 0 && $job->isLastBatch === false;
        });

        Queue::assertPushed(ExportProductsJob::class, function (ExportProductsJob $job) {
            return $job->batchIndex === 1 && $job->isLastBatch === false;
        });

        Queue::assertPushed(ExportProductsJob::class, function (ExportProductsJob $job) {
            return $job->batchIndex === 2 && $job->isLastBatch === true;
        });
    }
}
