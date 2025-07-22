<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Manufacturer;
use Illuminate\Database\Eloquent\Factories\Factory;

final class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word().' '.$this->faker->randomElement(['TV', 'Laptop', 'Phone', 'Refrigerator']),
            'description' => $this->faker->sentence(),
            'release_date' => $this->faker->date(),
            'price' => $this->faker->randomFloat(2, 100, 3000),
            'manufacturer_id' => Manufacturer::factory(),
        ];
    }
}
