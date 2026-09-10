<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\ApplicationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplyJobRequest;
use App\Http\Requests\UpdateApplicationStatusRequest;
use App\Http\Resources\ApplicationHistoryResource;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Models\Job;
use App\Services\ApplicationPipelineService;
use App\Services\ApplicationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApplicationController extends Controller
{
    public function __construct(
        protected ApplicationService $applicationService,
        protected ApplicationPipelineService $pipelineService
    ) {}

    /**
     * Candidate applies to a job.
     */
    public function apply(ApplyJobRequest $request, Job $job): JsonResponse
    {
        $application = $this->applicationService->apply(
            $request->user(),
            $job,
            $request->validated(),
            $request->file('resume')
        );

        return response()->json([
            'success' => true,
            'message' => 'Application submitted successfully.',
            'data' => new ApplicationResource($application->load(['job', 'resume', 'candidate.user'])),
        ], 201);
    }

    /**
     * List applications with filters.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $applications = $this->applicationService->listApplications($request->user(), $request->all());
        return ApplicationResource::collection($applications);
    }

    /**
     * Show application details.
     */
    public function show(Application $application): JsonResponse
    {
        $this->authorize('view', $application);

        $application->load([
            'job.skills',
            'candidate.user',
            'resume',
            'statusHistories.changedByUser',
            'interviews.interviewer',
            'technicalTasks.submissions.reviewer',
        ]);

        return response()->json([
            'success' => true,
            'data' => new ApplicationResource($application),
        ]);
    }

    /**
     * Transition application to a new pipeline stage.
     */
    public function updateStatus(UpdateApplicationStatusRequest $request, Application $application): JsonResponse
    {
        $targetStatus = ApplicationStatus::from($request->validated('status'));
        $remarks = $request->validated('remarks');

        $updated = $this->pipelineService->transition(
            $application,
            $targetStatus,
            $request->user(),
            $remarks
        );

        return response()->json([
            'success' => true,
            'message' => "Application status updated to '{$targetStatus->value}'.",
            'data' => new ApplicationResource($updated),
        ]);
    }

    /**
     * View audit history of status changes.
     */
    public function history(Application $application): JsonResponse
    {
        $this->authorize('viewHistory', $application);

        $histories = $this->pipelineService->getHistory($application);

        return response()->json([
            'success' => true,
            'data' => ApplicationHistoryResource::collection($histories),
        ]);
    }
}
