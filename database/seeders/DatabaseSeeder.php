<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Service;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $services = Service::factory()->count(50)->create();

        Product::factory()
            ->count(100)
            ->create()
            ->each(function ($product) use ($services) {
                $randomServices = $services->random(rand(1, 5));

                $syncData = [];
                foreach ($randomServices as $service) {
                    $syncData[$service->id] = [
                        'daysToExpire' => rand(1, 365),
                        'cost' => mt_rand(1000, 50000) / 100,
                    ];
                }

                $product->services()->sync($syncData);
            });
    }
}
