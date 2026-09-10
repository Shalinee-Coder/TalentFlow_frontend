<?php

namespace Tests\Feature;

use App\Enums\JobStatus;
use App\Models\Job;
use App\Models\Skill;
use App\Models\User;
use Tests\TestCase;

class JobTest extends TestCase
{
    public function test_can_list_published_jobs_publicly(): void
    {
        Job::factory()->published()->count(3)->create();
        Job::factory()->draft()->count(2)->create();

        $response = $this->getJson('/api/v1/jobs');

        $response->assertOk();
        $data = $response->json('data');
        $this->assertCount(3, $data);
    }

    public function test_recruiter_can_create_job_with_skills(): void
    {
        $recruiter = User::factory()->recruiter()->create();
        $skill1 = Skill::factory()->create(['name' => 'Laravel']);
        $skill2 = Skill::factory()->create(['name' => 'Docker']);

        $payload = [
            'title' => 'Senior Backend Engineer',
            'department' => 'Core Systems',
            'description' => 'Build high-performance microservices.',
            'required_experience' => 4.0,
            'salary_min' => 90000,
            'salary_max' => 120000,
            'application_deadline' => now()->addDays(14)->toIso8601String(),
            'status' => 'published',
            'skills' => [
                ['skill_id' => $skill1->id, 'is_required' => true, 'weight' => 5],
                ['skill_id' => $skill2->id, 'is_required' => false, 'weight' => 2],
            ],
        ];

        $response = $this->actingAs($recruiter, 'sanctum')->postJson('/api/v1/jobs', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.title', 'Senior Backend Engineer');

        $this->assertDatabaseHas('jobs', ['title' => 'Senior Backend Engineer']);
        $this->assertDatabaseHas('job_skills', [
            'skill_id' => $skill1->id,
            'is_required' => 1,
            'weight' => 5,
        ]);
    }

    public function test_recruiter_can_update_own_job(): void
    {
        $recruiter = User::factory()->recruiter()->create();
        $job = Job::factory()->create(['recruiter_id' => $recruiter->id, 'title' => 'Old Title']);

        $response = $this->actingAs($recruiter, 'sanctum')->putJson("/api/v1/jobs/{$job->id}", [
            'title' => 'Updated Title',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.title', 'Updated Title');

        $this->assertDatabaseHas('jobs', ['id' => $job->id, 'title' => 'Updated Title']);
    }

    public function test_recruiter_cannot_update_another_recruiters_job(): void
    {
        $recruiter1 = User::factory()->recruiter()->create();
        $recruiter2 = User::factory()->recruiter()->create();

        $job = Job::factory()->create(['recruiter_id' => $recruiter1->id, 'title' => 'Original Job']);

        $response = $this->actingAs($recruiter2, 'sanctum')->putJson("/api/v1/jobs/{$job->id}", [
            'title' => 'Hacked Job',
        ]);

        $response->assertStatus(403);
    }

    public function test_recruiter_can_update_job_status(): void
    {
        $recruiter = User::factory()->recruiter()->create();
        $job = Job::factory()->create(['recruiter_id' => $recruiter->id, 'status' => JobStatus::DRAFT->value]);

        $response = $this->actingAs($recruiter, 'sanctum')->patchJson("/api/v1/jobs/{$job->id}/status", [
            'status' => 'published',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.status', 'published');

        $this->assertEquals(JobStatus::PUBLISHED, $job->fresh()->status);
    }
}
