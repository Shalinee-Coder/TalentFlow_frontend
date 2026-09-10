<?php

namespace Database\Factories;

use App\Enums\TaskSubmissionStatus;
use App\Models\Candidate;
use App\Models\TaskSubmission;
use App\Models\TechnicalTask;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskSubmissionFactory extends Factory
{
    protected $model = TaskSubmission::class;

    public function definition(): array
    {
        return [
            'technical_task_id' => TechnicalTask::factory(),
            'candidate_id' => Candidate::factory(),
            'submission_content' => fake()->paragraph(),
            'submission_url' => 'https://github.com/' . fake()->userName() . '/assessment-solution',
            'submitted_at' => now(),
            'review_notes' => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
            'status' => TaskSubmissionStatus::SUBMITTED->value,
        ];
    }
}
