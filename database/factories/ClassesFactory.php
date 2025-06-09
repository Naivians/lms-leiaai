<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Classes>
 */
class ClassesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $courseNames = [
            'PPL BATCH 2025', 'CPL BATCH 2025', 'ATPL BATCH 2025', 'IR BATCH 2025', 'ME BATCH 2025',
        ];

        return [
            'class_name' => $this->faker->unique()->randomElement($courseNames),
            'class_description' => $this->faker->sentence(),
            'user_id' => $this->faker->numberBetween(1, 5),
            'course_id' => $this->faker->numberBetween(1, 10),
            'active' => $this->faker->numberBetween(0, 1),
            'class_code' => $this->faker->unique()->word(),
            'file_path' => $this->faker->filePath(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
