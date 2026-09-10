<?php

namespace App\Services;

use App\Enums\ApplicationStatus;
use App\Enums\ResumeStatus;
use App\Events\ApplicationSubmitted;
use App\Jobs\ProcessResumeJob;
use App\Models\Application;
use App\Models\ApplicationStatusHistory;
use App\Models\Candidate;
use App\Models\Job;
use App\Models\Resume;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ApplicationService
{
    public function __construct(
        protected CandidateScoringService $scoringService
    ) {}

    /**
     * Candidate applies to a job.
     */
    public function apply(User $candidateUser, Job $job, array $data, ?UploadedFile $resumeFile = null): Application
    {
        // 1. Verify job is accepting applications
        if (!$job->isAcceptingApplications()) {
            throw ValidationException::withMessages([
                'job' => ['This job posting is closed or has passed its application deadline.'],
            ]);
        }

        $candidate = $candidateUser->candidate;
        if (!$candidate) {
            $candidate = Candidate::create(['user_id' => $candidateUser->id]);
        }

        // 2. Prevent duplicate applications
        $existing = Application::where('job_id', $job->id)
            ->where('candidate_id', $candidate->id)
            ->first();

        if ($existing) {
            throw ValidationException::withMessages([
                'application' => ['You have already submitted an application for this job.'],
            ]);
        }

        return DB::transaction(function () use ($candidateUser, $candidate, $job, $data, $resumeFile) {
            // 3. Resolve or upload resume
            $resume = null;
            if ($resumeFile) {
                $uniqueName = (string) Str::uuid() . '.pdf';
                $path = $resumeFile->storeAs('', $uniqueName, 'resumes');

                $resume = Resume::create([
                    'candidate_id' => $candidate->id,
                    'file_path' => $path,
                    'original_filename' => $resumeFile->getClientOriginalName(),
                    'mime_type' => $resumeFile->getClientMimeType() ?: 'application/pdf',
                    'file_size' => $resumeFile->getSize(),
                    'processing_status' => ResumeStatus::UPLOADED->value,
                ]);

                // Dispatch resume processing job to queue
                ProcessResumeJob::dispatch($resume->id);

            } elseif (!empty($data['resume_id'])) {
                $resume = Resume::where('candidate_id', $candidate->id)->findOrFail($data['resume_id']);
            } else {
                // Try to find candidate's latest resume
                $resume = $candidate->latestResume;
                if (!$resume) {
                    throw ValidationException::withMessages([
                        'resume' => ['A valid PDF resume is required to apply.'],
                    ]);
                }
            }

            // 4. Calculate initial score if resume is already processed
            $initialScore = 0.0;
            if ($resume->extracted_data) {
                $initialScore = $this->scoringService->calculateScore($job, $resume->extracted_data);
            }

            // 5. Create Application
            $application = Application::create([
                'job_id' => $job->id,
                'candidate_id' => $candidate->id,
                'resume_id' => $resume->id,
                'score' => $initialScore,
                'current_status' => ApplicationStatus::APPLIED->value,
                'applied_at' => Carbon::now(),
            ]);

            // 6. Record Initial Status History
            ApplicationStatusHistory::create([
                'application_id' => $application->id,
                'from_status' => null,
                'to_status' => ApplicationStatus::APPLIED->value,
                'changed_by' => $candidateUser->id,
                'changed_at' => Carbon::now(),
                'remarks' => 'Application submitted by candidate.',
            ]);

            // 7. Dispatch Event
            event(new ApplicationSubmitted($application->load(['job.recruiter', 'candidate.user', 'resume'])));

            return $application;
        });
    }

    /**
     * List applications with role scoping, filtering, and sorting.
     */
    public function listApplications(User $user, array $filters = []): LengthAwarePaginator
    {
        $query = Application::with(['job', 'candidate.user', 'resume']);

        // Scoping by role
        if ($user->isCandidate()) {
            $query->where('candidate_id', $user->candidate?->id ?? 0);
        } elseif ($user->isRecruiter()) {
            $query->whereHas('job', fn($q) => $q->where('recruiter_id', $user->id));
        }

        // Job filter
        if (!empty($filters['job_id'])) {
            $query->where('job_id', $filters['job_id']);
        }

        // Status filter
        if (!empty($filters['status'])) {
            $query->where('current_status', $filters['status']);
        }

        // Min score filter
        if (isset($filters['min_score'])) {
            $query->where('score', '>=', $filters['min_score']);
        }

        // Sorting
        $sort = $filters['sort'] ?? 'latest';
        match ($sort) {
            'score_desc' => $query->orderBy('score', 'desc'),
            'score_asc' => $query->orderBy('score', 'asc'),
            'oldest' => $query->orderBy('applied_at', 'asc'),
            default => $query->orderBy('applied_at', 'desc'),
        };

        $perPage = min((int) ($filters['per_page'] ?? 15), 50);

        return $query->paginate($perPage);
    }
}
