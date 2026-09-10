<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\ResumeStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\UploadResumeRequest;
use App\Http\Resources\ResumeResource;
use App\Jobs\ProcessResumeJob;
use App\Models\Candidate;
use App\Models\Job;
use App\Models\Resume;
use App\Services\CandidateScoringService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResumeController extends Controller
{
    public function __construct(
        protected CandidateScoringService $scoringService
    ) {}

    /**
     * Upload a candidate PDF resume.
     */
    public function store(UploadResumeRequest $request): JsonResponse
    {
        $user = $request->user();
        $candidate = $user->isCandidate()
            ? ($user->candidate ?? Candidate::create(['user_id' => $user->id]))
            : Candidate::findOrFail($request->validated('candidate_id'));

        if ($user->isRecruiter()) {
            abort_unless(
                $candidate->applications()->whereHas('job', fn ($query) => $query->where('recruiter_id', $user->id))->exists(),
                403,
                'You can upload resumes only for candidates in your job applications.'
            );
        }

        $file = $request->file('resume');
        $uniqueFilename = (string) Str::uuid() . '.pdf';
        $storedPath = $file->storeAs('', $uniqueFilename, 'resumes');

        $resume = Resume::create([
            'candidate_id' => $candidate->id,
            'file_path' => $storedPath,
            'original_filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType() ?: 'application/pdf',
            'file_size' => $file->getSize(),
            'processing_status' => ResumeStatus::UPLOADED->value,
        ]);

        // Dispatch asynchronous processing job to queue
        ProcessResumeJob::dispatch($resume->id);

        return response()->json([
            'success' => true,
            'message' => 'Resume uploaded successfully and queued for processing.',
            'data' => new ResumeResource($resume),
        ], 201);
    }

    /**
     * Display resume metadata.
     */
    public function show(Resume $resume): JsonResponse
    {
        $this->authorize('view', $resume);

        return response()->json([
            'success' => true,
            'data' => new ResumeResource($resume),
        ]);
    }

    /**
     * Get resume processing status.
     */
    public function status(Resume $resume): JsonResponse
    {
        $this->authorize('view', $resume);
        $resume->loadMissing('candidate.user');
        $latestApplication = $resume->applications()->latest('created_at')->first();
        $score = $latestApplication?->score !== null ? (float) $latestApplication->score : null;
        $scoreJobTitle = $latestApplication?->job?->title;

        if ($score === null && $resume->isProcessed() && $resume->extracted_data) {
            $scoreJob = Job::where('status', 'published')->latest('created_at')->first();
            if ($scoreJob) {
                $score = $this->scoringService->calculateScore($scoreJob, $resume->extracted_data);
                $scoreJobTitle = $scoreJob->title;
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $resume->id,
                'status' => $resume->processing_status->value ?? $resume->processing_status,
                'processed_at' => $resume->processed_at?->toIso8601String(),
                'is_processed' => $resume->isProcessed(),
                'score' => $score,
                'score_job_title' => $scoreJobTitle,
                'candidate_name' => $resume->candidate?->user?->name,
                'extracted_data' => $resume->extracted_data,
            ],
        ]);
    }

    /**
     * Download the resume PDF securely without exposing raw storage path.
     */
    public function download(Resume $resume): StreamedResponse|JsonResponse
    {
        $this->authorize('download', $resume);

        if (!Storage::disk('resumes')->exists($resume->file_path)) {
            return response()->json([
                'success' => false,
                'message' => 'Resume file not found on disk.',
            ], 404);
        }

        return Storage::disk('resumes')->download(
            $resume->file_path,
            $resume->original_filename,
            ['Content-Type' => 'application/pdf']
        );
    }
}
