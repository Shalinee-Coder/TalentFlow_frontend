<?php

namespace Database\Factories;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\Job;
use App\Models\Resume;
use Illuminate\Database\Eloquent\Factories\Factory;

class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    public function definition(): array
    {
        return [
            'job_id' => Job::factory(),
            'candidate_id' => Candidate::factory(),
            'resume_id' => Resume::factory(),
            'score' => fake()->randomFloat(2, 45, 95),
            'current_status' => ApplicationStatus::APPLIED->value,
            'applied_at' => now(),
        ];
    }
}
