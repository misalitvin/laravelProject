<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class Product_ServiceFactory extends Factory
{
    public function definition()
    {
        return [
            'daysToExpire' => $this->faker->numberBetween(1, 365),
            'cost' => $this->faker->randomFloat(2, 10, 500),
        ];
    }
}
