<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(['Warranty Service', 'Delivery', 'Installation', 'Customization']),
        ];
    }
}
