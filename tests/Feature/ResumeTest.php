<?php

namespace Tests\Feature;

use App\Jobs\ProcessResumeJob;
use App\Models\Candidate;
use App\Models\Application;
use App\Models\Job;
use App\Models\Resume;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ResumeTest extends TestCase
{
    public function test_candidate_can_upload_pdf_resume_and_job_is_dispatched(): void
    {
        Queue::fake();
        Storage::fake('resumes');

        $user = User::factory()->candidate()->create();
        $candidate = Candidate::factory()->create(['user_id' => $user->id]);

        $file = UploadedFile::fake()->create('my_resume.pdf', 500, 'application/pdf');

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/resumes', [
            'resume' => $file,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.original_filename', 'my_resume.pdf');

        $this->assertDatabaseHas('resumes', [
            'candidate_id' => $candidate->id,
            'original_filename' => 'my_resume.pdf',
        ]);

        Queue::assertPushed(ProcessResumeJob::class);
    }

    public function test_non_pdf_resume_upload_is_rejected(): void
    {
        Storage::fake('resumes');

        $user = User::factory()->candidate()->create();
        Candidate::factory()->create(['user_id' => $user->id]);

        $file = UploadedFile::fake()->create('malicious.docx', 500, 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/resumes', [
            'resume' => $file,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['resume']);
    }

    public function test_candidate_cannot_view_another_candidates_resume(): void
    {
        $user1 = User::factory()->candidate()->create();
        $cand1 = Candidate::factory()->create(['user_id' => $user1->id]);
        $resume1 = Resume::factory()->create(['candidate_id' => $cand1->id]);

        $user2 = User::factory()->candidate()->create();
        Candidate::factory()->create(['user_id' => $user2->id]);

        $response = $this->actingAs($user2, 'sanctum')->getJson("/api/v1/resumes/{$resume1->id}");

        $response->assertStatus(403);
    }

    public function test_recruiter_can_upload_resume_for_candidate_on_own_job(): void
    {
        Storage::fake('resumes');

        $recruiter = User::factory()->recruiter()->create();
        $candidateUser = User::factory()->candidate()->create();
        $candidate = Candidate::factory()->create(['user_id' => $candidateUser->id]);
        $job = Job::factory()->create(['recruiter_id' => $recruiter->id]);
        Application::factory()->create([
            'job_id' => $job->id,
            'candidate_id' => $candidate->id,
        ]);

        $response = $this->actingAs($recruiter, 'sanctum')->postJson('/api/v1/resumes', [
            'candidate_id' => $candidate->id,
            'resume' => UploadedFile::fake()->create('candidate.pdf', 500, 'application/pdf'),
        ]);

        $response->assertStatus(201)->assertJsonPath('success', true);
        $this->assertDatabaseHas('resumes', [
            'candidate_id' => $candidate->id,
            'original_filename' => 'candidate.pdf',
        ]);
    }

    public function test_recruiter_cannot_upload_for_unrelated_candidate(): void
    {
        Storage::fake('resumes');

        $recruiter = User::factory()->recruiter()->create();
        $candidate = Candidate::factory()->create();

        $response = $this->actingAs($recruiter, 'sanctum')->postJson('/api/v1/resumes', [
            'candidate_id' => $candidate->id,
            'resume' => UploadedFile::fake()->create('candidate.pdf', 500, 'application/pdf'),
        ]);

        $response->assertStatus(403);
    }
}
