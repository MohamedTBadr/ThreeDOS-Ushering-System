<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RegistrationFactory extends Factory
{
    public function definition(): array
    {
        $councils = ['Backend Development', 'Frontend Development', 'CEO', 'Marketing', 'Stock Market'];
        $levels = ['Level 1', 'Level 2', 'Level 3', 'Level 4'];
        $ratings = ['Pending', 'Acceptance', 'B', 'Rejection'];
        $colleges = ['Cairo University', 'Ain Shams University', 'Helwan University', 'Mansoura University', 'Alexandria University', 'HU'];
        $eventTypes = ['Offline', 'Online'];

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => '01' . fake()->numberBetween(0, 2) . fake()->numerify('#########'),
            'college' => fake()->randomElement($colleges),
            'level' => fake()->randomElement($levels),
            'council' => fake()->randomElement($councils),
            'event_type' => fake()->randomElement($eventTypes),
            'ushered_by' => fake()->optional(0.7)->name(),
            'rating' => fake()->randomElement($ratings),
            'notes' => fake()->optional(0.3)->sentence(),
            'interview_time' => fake()->optional(0.4)->dateTimeBetween('now', '+14 days'),
            'interviewed_by' => fake()->optional(0.4)->userName(),
            'interview_questions' => null,
            'interview_notes' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn() => ['rating' => 'Pending', 'interview_time' => null]);
    }

    public function accepted(): static
    {
        return $this->state(fn() => ['rating' => 'Acceptance']);
    }
}
