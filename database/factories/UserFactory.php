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
            'id_number' => $this->faker->firstName .'0000' . Str::random(10),
            'fname' => $this->faker->firstName,
            'lname' => $this->faker->lastName,
            'mname' => $this->faker->firstName,
            'suffix' => $this->faker->optional()->suffix,
            'contact' => $this->faker->numerify('09#########'),
            'email' => $this->faker->unique()->safeEmail,
            'password' => bcrypt('password'),
            'role' => $this->faker->randomElement([0, 5]),
            'isVerified' => $this->faker->boolean,
            'login_status' => $this->faker->boolean,
            'img' => 'https://i.pravatar.cc/150?u=' . $this->faker->unique()->email(),
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
