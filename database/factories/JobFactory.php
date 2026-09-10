<?php

namespace Database\Factories;

use App\Enums\JobStatus;
use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobFactory extends Factory
{
    protected $model = Job::class;

    public function definition(): array
    {
        return [
            'recruiter_id' => User::factory()->recruiter(),
            'title' => fake()->jobTitle(),
            'department' => fake()->randomElement(['Engineering', 'Product', 'Design', 'Data Science', 'Operations']),
            'description' => fake()->paragraphs(3, true),
            'required_experience' => fake()->randomFloat(1, 1, 8),
            'salary_min' => fake()->numberBetween(60000, 90000),
            'salary_max' => fake()->numberBetween(100000, 160000),
            'application_deadline' => now()->addDays(fake()->numberBetween(7, 30)),
            'status' => JobStatus::PUBLISHED->value,
        ];
    }

    public function published(): static
    {
        return $this->state(fn() => [
            'status' => JobStatus::PUBLISHED->value,
            'application_deadline' => now()->addDays(14),
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn() => [
            'status' => JobStatus::DRAFT->value,
        ]);
    }

    public function closed(): static
    {
        return $this->state(fn() => [
            'status' => JobStatus::CLOSED->value,
            'application_deadline' => now()->subDay(),
        ]);
    }
}
