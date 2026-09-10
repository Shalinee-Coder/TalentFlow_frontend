<?php

namespace Tests\Feature;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\Candidate;
use App\Models\Job;
use App\Models\Resume;
use App\Models\User;
use Tests\TestCase;

class ApplicationPipelineTest extends TestCase
{
    public function test_candidate_can_apply_for_open_job(): void
    {
        $candidateUser = User::factory()->candidate()->create();
        $candidate = Candidate::factory()->create(['user_id' => $candidateUser->id]);
        $resume = Resume::factory()->create(['candidate_id' => $candidate->id]);
        $job = Job::factory()->published()->create();

        $response = $this->actingAs($candidateUser, 'sanctum')->postJson("/api/v1/jobs/{$job->id}/apply", [
            'resume_id' => $resume->id,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.current_status', 'applied');

        $this->assertDatabaseHas('applications', [
            'job_id' => $job->id,
            'candidate_id' => $candidate->id,
        ]);

        $this->assertDatabaseHas('application_status_histories', [
            'to_status' => 'applied',
        ]);
    }

    public function test_candidate_cannot_apply_twice_to_same_job(): void
    {
        $candidateUser = User::factory()->candidate()->create();
        $candidate = Candidate::factory()->create(['user_id' => $candidateUser->id]);
        $resume = Resume::factory()->create(['candidate_id' => $candidate->id]);
        $job = Job::factory()->published()->create();

        Application::factory()->create([
            'job_id' => $job->id,
            'candidate_id' => $candidate->id,
            'resume_id' => $resume->id,
        ]);

        $response = $this->actingAs($candidateUser, 'sanctum')->postJson("/api/v1/jobs/{$job->id}/apply", [
            'resume_id' => $resume->id,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['application']);
    }

    public function test_recruiter_can_advance_application_through_valid_pipeline(): void
    {
        $recruiter = User::factory()->recruiter()->create();
        $job = Job::factory()->create(['recruiter_id' => $recruiter->id]);
        $app = Application::factory()->create([
            'job_id' => $job->id,
            'current_status' => ApplicationStatus::APPLIED->value,
        ]);

        // Transition: Applied -> Screening
        $response = $this->actingAs($recruiter, 'sanctum')
            ->patchJson("/api/v1/applications/{$app->id}/status", [
                'status' => 'screening',
                'remarks' => 'Reviewing qualifications',
            ]);

        $response->assertOk()
            ->assertJsonPath('data.current_status', 'screening');

        $this->assertDatabaseHas('application_status_histories', [
            'application_id' => $app->id,
            'from_status' => 'applied',
            'to_status' => 'screening',
            'remarks' => 'Reviewing qualifications',
        ]);
    }

    public function test_invalid_pipeline_transition_is_blocked(): void
    {
        $recruiter = User::factory()->recruiter()->create();
        $job = Job::factory()->create(['recruiter_id' => $recruiter->id]);
        $app = Application::factory()->create([
            'job_id' => $job->id,
            'current_status' => ApplicationStatus::APPLIED->value,
        ]);

        // Illegal Transition: Applied -> Hired
        $response = $this->actingAs($recruiter, 'sanctum')
            ->patchJson("/api/v1/applications/{$app->id}/status", [
                'status' => 'hired',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }

    public function test_candidate_cannot_change_application_status(): void
    {
        $candidateUser = User::factory()->candidate()->create();
        $candidate = Candidate::factory()->create(['user_id' => $candidateUser->id]);
        $app = Application::factory()->create(['candidate_id' => $candidate->id]);

        $response = $this->actingAs($candidateUser, 'sanctum')
            ->patchJson("/api/v1/applications/{$app->id}/status", [
                'status' => 'screening',
            ]);

        $response->assertStatus(403);
    }
}
