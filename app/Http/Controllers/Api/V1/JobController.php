<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\JobStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Http\Requests\UpdateJobStatusRequest;
use App\Http\Resources\JobResource;
use App\Models\Job;
use App\Services\JobService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class JobController extends Controller
{
    public function __construct(
        protected JobService $jobService
    ) {}

    /**
     * List jobs with filters and pagination.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $jobs = $this->jobService->listJobs($request->all(), $request->user());
        return JobResource::collection($jobs);
    }

    /**
     * Store a new job.
     */
    public function store(StoreJobRequest $request): JsonResponse
    {
        $job = $this->jobService->createJob($request->user(), $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Job created successfully.',
            'data' => new JobResource($job),
        ], 201);
    }

    /**
     * Display a specific job.
     */
    public function show(Job $job): JsonResponse
    {
        $this->authorize('view', $job);

        $job->load(['recruiter', 'skills'])->loadCount('applications');

        return response()->json([
            'success' => true,
            'data' => new JobResource($job),
        ]);
    }

    /**
     * Update job details.
     */
    public function update(UpdateJobRequest $request, Job $job): JsonResponse
    {
        $updated = $this->jobService->updateJob($job, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Job updated successfully.',
            'data' => new JobResource($updated),
        ]);
    }

    /**
     * Update job status (draft/published/closed).
     */
    public function updateStatus(UpdateJobStatusRequest $request, Job $job): JsonResponse
    {
        $status = JobStatus::from($request->validated('status'));
        $updated = $this->jobService->updateStatus($job, $status);

        return response()->json([
            'success' => true,
            'message' => "Job status updated to {$status->value}.",
            'data' => new JobResource($updated),
        ]);
    }

    /**
     * Delete a job.
     */
    public function destroy(Job $job): JsonResponse
    {
        $this->authorize('delete', $job);

        $this->jobService->deleteJob($job);

        return response()->json([
            'success' => true,
            'message' => 'Job deleted successfully.',
        ]);
    }
}
