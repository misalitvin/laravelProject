<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Service;
use Illuminate\Database\Seeder;

final class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $services = Service::all();

        Product::factory()
            ->count(10)
            ->create()
            ->each(function ($product) use ($services) {
                $randomServices = $services->random(rand(1, 4));

                $syncData = [];
                foreach ($randomServices as $service) {
                    $syncData[$service->id] = [
                        'days_to_complete' => rand(1, 365),
                        'cost' => mt_rand(1000, 50000) / 100,
                    ];
                }

                $product->services()->sync($syncData);
            });
    }
}
