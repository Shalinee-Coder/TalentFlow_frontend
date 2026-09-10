<?php

namespace Database\Factories;

use App\Enums\TechnicalTaskStatus;
use App\Models\Application;
use App\Models\TechnicalTask;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TechnicalTaskFactory extends Factory
{
    protected $model = TechnicalTask::class;

    public function definition(): array
    {
        return [
            'application_id' => Application::factory(),
            'recruiter_id' => User::factory()->recruiter(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'instructions' => fake()->paragraph(),
            'assigned_at' => now(),
            'due_at' => now()->addDays(5),
            'status' => TechnicalTaskStatus::PENDING->value,
            'reminder_sent_at' => null,
        ];
    }
}
