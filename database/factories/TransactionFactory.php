<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $seller = Seller::has('products')
            ->get()
            ->random();
        # wrong as it may return a uses that is seller at the same time
        //$buyer = User::all()->except($seller->id)->random();
        $buyer = User::whereNotIn('id', Product::all('seller_id'))->get()->random();
        return [
            'quantity' => $this->faker->numberBetween(1, 10),
            'buyer_id' => $buyer->id,
            'product_id' => $seller->products->random()->id,
        ];
    }
}
