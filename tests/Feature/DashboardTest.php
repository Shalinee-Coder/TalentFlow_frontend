<?php

namespace Tests\Feature;

use App\Enums\ApplicationStatus;
use App\Enums\InterviewStatus;
use App\Models\Application;
use App\Models\Interview;
use App\Models\Job;
use App\Models\User;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    public function test_admin_can_view_dashboard_overview(): void
    {
        $admin = User::factory()->admin()->create();

        Job::factory()->published()->count(3)->create();
        Application::factory()->count(5)->create();

        $response = $this->actingAs($admin, 'sanctum')->getJson('/api/v1/dashboard/overview');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'total_jobs',
                    'published_jobs',
                    'total_applications',
                    'active_candidates',
                    'interviews_this_week',
                    'average_candidate_score',
                ],
            ]);
    }

    public function test_dashboard_pipeline_distribution_returns_all_stages(): void
    {
        $recruiter = User::factory()->recruiter()->create();
        $job = Job::factory()->create(['recruiter_id' => $recruiter->id]);

        Application::factory()->create([
            'job_id' => $job->id,
            'current_status' => ApplicationStatus::APPLIED->value,
        ]);
        Application::factory()->create([
            'job_id' => $job->id,
            'current_status' => ApplicationStatus::SCREENING->value,
        ]);

        $response = $this->actingAs($recruiter, 'sanctum')->getJson('/api/v1/dashboard/pipeline');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.Applied', 1)
            ->assertJsonPath('data.Screening', 1)
            ->assertJsonPath('data.Shortlisted', 0);
    }

    public function test_candidate_can_access_personal_dashboard(): void
    {
        $candidate = User::factory()->candidate()->create();

        $response = $this->actingAs($candidate, 'sanctum')->getJson('/api/v1/dashboard/overview');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.total_applications', 0)
            ->assertJsonPath('data.total_jobs', 0);
    }

    public function test_dashboard_returns_live_application_and_interview_counters(): void
    {
        $recruiter = User::factory()->recruiter()->create();
        $job = Job::factory()->create(['recruiter_id' => $recruiter->id]);

        $shortlisted = Application::factory()->create([
            'job_id' => $job->id,
            'current_status' => ApplicationStatus::SHORTLISTED->value,
        ]);
        $screening = Application::factory()->create([
            'job_id' => $job->id,
            'current_status' => ApplicationStatus::SCREENING->value,
        ]);

        Interview::factory()->create([
            'application_id' => $shortlisted->id,
            'interviewer_id' => $recruiter->id,
            'status' => InterviewStatus::SCHEDULED->value,
        ]);
        Interview::factory()->create([
            'application_id' => $screening->id,
            'interviewer_id' => $recruiter->id,
            'status' => InterviewStatus::COMPLETED->value,
        ]);

        $response = $this->actingAs($recruiter, 'sanctum')->getJson('/api/v1/dashboard/overview');

        $response->assertOk()
            ->assertJsonPath('data.total_applications', 2)
            ->assertJsonPath('data.shortlisted_applications', 1)
            ->assertJsonPath('data.scheduled_interviews', 1)
            ->assertJsonPath('data.pipeline_counts.shortlisted', 1);
    }
}
