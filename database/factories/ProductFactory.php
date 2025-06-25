<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->word() . ' ' . $this->faker->randomElement(['TV', 'Laptop', 'Phone', 'Refrigerator']),
            'description' => $this->faker->sentence(),
            'manufacturer' => $this->faker->company(),
            'release_date' => $this->faker->date(),
            'price' => $this->faker->randomFloat(2, 100, 3000),
        ];
    }
}
