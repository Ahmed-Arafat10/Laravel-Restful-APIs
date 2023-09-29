<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->paragraph(2),
            'quantity' => $this->faker->numberBetween(1,10),
            'status' => $this->faker->boolean,
            'image' => $this->faker->imageUrl,
            'seller_id' => User::inRandomOrder()->first()->id , // User::all()->random()->id
        ];
    }
}
