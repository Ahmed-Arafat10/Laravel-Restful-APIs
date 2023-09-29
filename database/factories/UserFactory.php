<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'), // You can change 'password' to your desired default password
            'remember_token' => Str::random(10),
            'verified' => $verified = $this->faker->randomElement([User::UNVERIFIED_USER, User::VERIFIED_USER]),
            'verification_token' => $verified == User::UNVERIFIED_USER ? user::generateVerificationCode() : null, // or generate a verification token if needed
            'admin' => $this->faker->randomElement([User::REGULAR_USER, User::ADMIN_USER]),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
