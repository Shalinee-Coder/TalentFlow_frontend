<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Interview;
use App\Models\Job;
use App\Models\User;
use Carbon\Carbon;
use Tests\TestCase;

class InterviewSchedulerTest extends TestCase
{
    public function test_recruiter_can_schedule_interview_without_conflict(): void
    {
        $recruiter = User::factory()->recruiter()->create();
        $job = Job::factory()->create(['recruiter_id' => $recruiter->id]);
        $app = Application::factory()->create(['job_id' => $job->id]);

        $scheduledAt = Carbon::now()->addDay()->setHour(10)->setMinute(0)->setSecond(0);

        $payload = [
            'interviewer_id' => $recruiter->id,
            'scheduled_at' => $scheduledAt->toIso8601String(),
            'duration' => 60,
            'meeting_link' => 'https://meet.google.com/abc-def-ghi',
            'notes' => 'First round technical discussion',
        ];

        $response = $this->actingAs($recruiter, 'sanctum')
            ->postJson("/api/v1/applications/{$app->id}/interviews", $payload);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.duration', 60);

        $this->assertDatabaseHas('interviews', [
            'application_id' => $app->id,
            'interviewer_id' => $recruiter->id,
            'duration' => 60,
        ]);
    }

    public function test_exact_overlap_is_blocked(): void
    {
        $recruiter = User::factory()->recruiter()->create();
        $job = Job::factory()->create(['recruiter_id' => $recruiter->id]);
        $app1 = Application::factory()->create(['job_id' => $job->id]);
        $app2 = Application::factory()->create(['job_id' => $job->id]);

        $baseTime = Carbon::now()->addDay()->setHour(10)->setMinute(0)->setSecond(0);

        // Existing interview: 10:00 - 11:00 (duration: 60)
        Interview::factory()->create([
            'application_id' => $app1->id,
            'interviewer_id' => $recruiter->id,
            'scheduled_at' => $baseTime,
            'duration' => 60,
        ]);

        // Attempt exact overlap: 10:00 - 11:00
        $response = $this->actingAs($recruiter, 'sanctum')
            ->postJson("/api/v1/applications/{$app2->id}/interviews", [
                'interviewer_id' => $recruiter->id,
                'scheduled_at' => $baseTime->toIso8601String(),
                'duration' => 60,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['scheduled_at']);
    }

    public function test_partial_overlap_is_blocked(): void
    {
        $recruiter = User::factory()->recruiter()->create();
        $job = Job::factory()->create(['recruiter_id' => $recruiter->id]);
        $app1 = Application::factory()->create(['job_id' => $job->id]);
        $app2 = Application::factory()->create(['job_id' => $job->id]);

        $baseTime = Carbon::now()->addDay()->setHour(10)->setMinute(0)->setSecond(0);

        // Existing: 10:00 - 11:00
        Interview::factory()->create([
            'application_id' => $app1->id,
            'interviewer_id' => $recruiter->id,
            'scheduled_at' => $baseTime,
            'duration' => 60,
        ]);

        // Attempt partial overlap: 10:30 - 11:30
        $overlapTime = $baseTime->copy()->addMinutes(30);
        $response = $this->actingAs($recruiter, 'sanctum')
            ->postJson("/api/v1/applications/{$app2->id}/interviews", [
                'interviewer_id' => $recruiter->id,
                'scheduled_at' => $overlapTime->toIso8601String(),
                'duration' => 60,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['scheduled_at']);
    }

    public function test_back_to_back_interviews_are_allowed(): void
    {
        $recruiter = User::factory()->recruiter()->create();
        $job = Job::factory()->create(['recruiter_id' => $recruiter->id]);
        $app1 = Application::factory()->create(['job_id' => $job->id]);
        $app2 = Application::factory()->create(['job_id' => $job->id]);

        $baseTime = Carbon::now()->addDay()->setHour(10)->setMinute(0)->setSecond(0);

        // Existing: 10:00 - 11:00
        Interview::factory()->create([
            'application_id' => $app1->id,
            'interviewer_id' => $recruiter->id,
            'scheduled_at' => $baseTime,
            'duration' => 60,
        ]);

        // Back-to-back: 11:00 - 12:00
        $backToBackTime = $baseTime->copy()->addMinutes(60);
        $response = $this->actingAs($recruiter, 'sanctum')
            ->postJson("/api/v1/applications/{$app2->id}/interviews", [
                'interviewer_id' => $recruiter->id,
                'scheduled_at' => $backToBackTime->toIso8601String(),
                'duration' => 60,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true);
    }

    public function test_different_interviewer_at_same_time_is_allowed(): void
    {
        $recruiter1 = User::factory()->recruiter()->create();
        $recruiter2 = User::factory()->recruiter()->create();
        $job = Job::factory()->create(['recruiter_id' => $recruiter1->id]);
        $app1 = Application::factory()->create(['job_id' => $job->id]);
        $app2 = Application::factory()->create(['job_id' => $job->id]);

        $baseTime = Carbon::now()->addDay()->setHour(14)->setMinute(0)->setSecond(0);

        // Recruiter 1 has interview at 14:00 - 15:00
        Interview::factory()->create([
            'application_id' => $app1->id,
            'interviewer_id' => $recruiter1->id,
            'scheduled_at' => $baseTime,
            'duration' => 60,
        ]);

        // Recruiter 2 scheduled at exact same time 14:00 - 15:00
        $response = $this->actingAs($recruiter2, 'sanctum')
            ->postJson("/api/v1/applications/{$app2->id}/interviews", [
                'interviewer_id' => $recruiter2->id,
                'scheduled_at' => $baseTime->toIso8601String(),
                'duration' => 60,
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true);
    }
}
