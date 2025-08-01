<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

final class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $serviceNames = ['Warranty', 'Delivery', 'Installation', 'Customization'];

        foreach ($serviceNames as $name) {
            Service::factory()->create(['name' => $name]);
        }
    }
}
