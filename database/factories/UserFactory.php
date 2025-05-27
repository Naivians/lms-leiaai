<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->faker->firstName .'0000' . Str::random(10),
            'fname' => $this->faker->firstName,
            'lname' => $this->faker->lastName,
            'mname' => $this->faker->firstName, // ✅ no middleName in default Faker, fallback to firstName
            'suffix' => $this->faker->optional()->suffix,
            'email' => $this->faker->unique()->safeEmail,
            'isVerified' => $this->faker->boolean, // ✅ more semantic than numberBetween(0, 1)
            'password' => bcrypt('password'), // ✅ you could also use Hash::make
            'contact' => $this->faker->numerify('09#########'), // ✅ realistic PH phone number
            'img' => 'https://i.pravatar.cc/150?u=' . $this->faker->unique()->email(),
            'role' => $this->faker->randomElement([0, 4]), // ✅ ensures only valid role values
            'remember_token' => Str::random(10),
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
