<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Announcement;
use App\Models\Classes; // Assuming your class model is called Classroom
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Announcement>
 */
class AnnouncementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'class_id' => Classes::inRandomOrder()->first()?->id ?? 1,
            'user_id' => User::inRandomOrder()->first()?->id ?? 1,
            'title' => $this->faker->sentence(6),
            'content' => $this->faker->paragraph(3),
            'announcement_date' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'is_pinned' => $this->faker->boolean(20), // 20% chance of being pinned
            'attachment' => null,
            'attachment_type' => null,
            'attachment_url' => null,
        ];
    }
}
