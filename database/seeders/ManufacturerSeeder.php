<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Manufacturer;
use Illuminate\Database\Seeder;

final class ManufacturerSeeder extends Seeder
{
    public function run(): void
    {
        Manufacturer::factory()->count(10)->create();
    }
}
