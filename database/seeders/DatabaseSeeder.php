<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Service;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $serviceNames = ['Warranty', 'Delivery', 'Installation', 'Customization'];
        $services = collect();

        foreach ($serviceNames as $name) {
            $services->push(Service::factory()->create(['name' => $name]));
        }

        Product::factory()
            ->count(10)
            ->create()
            ->each(function ($product) use ($services) {
                $randomServices = $services->random(rand(1, 4));

                $syncData = [];
                foreach ($randomServices as $service) {
                    $syncData[$service->id] = [
                        'days_to_complete' => rand(1, 365),
                        'cost' => mt_rand(1000, 50000)/100,
                    ];
                }

                $product->services()->sync($syncData);
            });
    }
}
