<?php

namespace Tests\Feature;

use App\Enums\TaskSubmissionStatus;
use App\Enums\TechnicalTaskStatus;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\Job;
use App\Models\TaskSubmission;
use App\Models\TechnicalTask;
use App\Models\User;
use Tests\TestCase;

class TechnicalTaskTest extends TestCase
{
    public function test_recruiter_can_assign_technical_task(): void
    {
        $recruiter = User::factory()->recruiter()->create();
        $job = Job::factory()->create(['recruiter_id' => $recruiter->id]);
        $app = Application::factory()->create(['job_id' => $job->id]);

        $payload = [
            'title' => 'Build a Rate Limiter Middleware in Laravel',
            'description' => 'Create a token bucket algorithm with Redis backend.',
            'instructions' => 'Include unit tests and benchmark stats.',
            'due_at' => now()->addDays(5)->toIso8601String(),
        ];

        $response = $this->actingAs($recruiter, 'sanctum')
            ->postJson("/api/v1/applications/{$app->id}/tasks", $payload);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Build a Rate Limiter Middleware in Laravel');

        $this->assertDatabaseHas('technical_tasks', [
            'application_id' => $app->id,
            'recruiter_id' => $recruiter->id,
            'status' => 'pending',
        ]);
    }

    public function test_candidate_can_submit_assigned_task(): void
    {
        $candidateUser = User::factory()->candidate()->create();
        $candidate = Candidate::factory()->create(['user_id' => $candidateUser->id]);
        $app = Application::factory()->create(['candidate_id' => $candidate->id]);

        $task = TechnicalTask::factory()->create([
            'application_id' => $app->id,
            'status' => TechnicalTaskStatus::PENDING->value,
            'due_at' => now()->addDays(3),
        ]);

        $payload = [
            'submission_url' => 'https://github.com/candidate/rate-limiter',
            'submission_content' => 'Completed all requirements with 100% test coverage.',
        ];

        $response = $this->actingAs($candidateUser, 'sanctum')
            ->postJson("/api/v1/tasks/{$task->id}/submit", $payload);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.submission_url', 'https://github.com/candidate/rate-limiter');

        $this->assertDatabaseHas('task_submissions', [
            'technical_task_id' => $task->id,
            'candidate_id' => $candidate->id,
        ]);

        $this->assertEquals(TechnicalTaskStatus::SUBMITTED, $task->fresh()->status);
    }

    public function test_candidate_cannot_submit_overdue_task(): void
    {
        $candidateUser = User::factory()->candidate()->create();
        $candidate = Candidate::factory()->create(['user_id' => $candidateUser->id]);
        $app = Application::factory()->create(['candidate_id' => $candidate->id]);

        $task = TechnicalTask::factory()->create([
            'application_id' => $app->id,
            'status' => TechnicalTaskStatus::PENDING->value,
            'due_at' => now()->subDay(), // Past due
        ]);

        $response = $this->actingAs($candidateUser, 'sanctum')
            ->postJson("/api/v1/tasks/{$task->id}/submit", [
                'submission_url' => 'https://github.com/late/submission',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['task']);
    }

    public function test_recruiter_can_review_task_submission(): void
    {
        $recruiter = User::factory()->recruiter()->create();
        $task = TechnicalTask::factory()->create(['recruiter_id' => $recruiter->id]);
        $submission = TaskSubmission::factory()->create(['technical_task_id' => $task->id]);

        $response = $this->actingAs($recruiter, 'sanctum')
            ->patchJson("/api/v1/submissions/{$submission->id}/review", [
                'status' => TaskSubmissionStatus::ACCEPTED->value,
                'review_notes' => 'Excellent architecture and test suite.',
            ]);

        $response->assertOk()
            ->assertJsonPath('data.status', 'accepted')
            ->assertJsonPath('data.review_notes', 'Excellent architecture and test suite.');

        $this->assertEquals(TaskSubmissionStatus::ACCEPTED, $submission->fresh()->status);
        $this->assertEquals(TechnicalTaskStatus::REVIEWED, $task->fresh()->status);
    }

    public function test_candidate_cannot_mark_task_as_reviewed(): void
    {
        $candidateUser = User::factory()->candidate()->create();
        $candidate = Candidate::factory()->create(['user_id' => $candidateUser->id]);
        $app = Application::factory()->create(['candidate_id' => $candidate->id]);
        $task = TechnicalTask::factory()->create([
            'application_id' => $app->id,
            'status' => TechnicalTaskStatus::PENDING->value,
        ]);

        $response = $this->actingAs($candidateUser, 'sanctum')
            ->patchJson("/api/v1/tasks/{$task->id}/status", [
                'status' => TechnicalTaskStatus::REVIEWED->value,
            ]);

        $response->assertForbidden();
        $this->assertEquals(TechnicalTaskStatus::PENDING, $task->fresh()->status);
    }
}
