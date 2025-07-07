<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

final class ManufacturerFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->company(),
        ];
    }
}
