<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

final class Product_ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, float|int>
     */
    public function definition(): array
    {
        return [
            'days_to_complete' => $this->faker->numberBetween(1, 365),
            'cost' => $this->faker->randomFloat(2, 10, 500),
        ];
    }
}
