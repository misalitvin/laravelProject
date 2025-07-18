<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

final class ServiceFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->randomElement(['Warranty Service', 'Delivery', 'Installation', 'Customization']),
        ];
    }
}
