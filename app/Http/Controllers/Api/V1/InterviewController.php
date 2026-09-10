<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScheduleInterviewRequest;
use App\Http\Requests\UpdateInterviewRequest;
use App\Http\Resources\InterviewResource;
use App\Models\Application;
use App\Models\Interview;
use App\Services\InterviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class InterviewController extends Controller
{
    public function __construct(
        protected InterviewService $interviewService
    ) {}

    /**
     * Schedule an interview with conflict checking.
     */
    public function store(ScheduleInterviewRequest $request, Application $application): JsonResponse
    {
        $interviewerId = $request->validated('interviewer_id') ?? $request->user()->id;
        $canManageApplication = $request->user()->isAdmin() ||
            $request->user()->id === $application->job?->recruiter_id ||
            $request->user()->id === (int) $interviewerId;

        abort_unless($canManageApplication, 403, 'You are not authorized to schedule an interview for this application.');

        $interview = $this->interviewService->schedule(
            $application,
            $request->validated(),
            $request->user()
        );

        return response()->json([
            'success' => true,
            'message' => 'Interview scheduled successfully without conflicts.',
            'data' => new InterviewResource($interview),
        ], 201);
    }

    /**
     * List scheduled interviews.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $interviews = $this->interviewService->listInterviews($request->user(), $request->all());
        return InterviewResource::collection($interviews);
    }

    /**
     * Show interview details.
     */
    public function show(Interview $interview): JsonResponse
    {
        $this->authorize('view', $interview);

        $interview->load(['application.job', 'application.candidate.user', 'interviewer']);

        return response()->json([
            'success' => true,
            'data' => new InterviewResource($interview),
        ]);
    }

    /**
     * Reschedule or update an interview.
     */
    public function update(UpdateInterviewRequest $request, Interview $interview): JsonResponse
    {
        $this->authorize('update', $interview);

        $updated = $this->interviewService->update($interview, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Interview updated successfully.',
            'data' => new InterviewResource($updated),
        ]);
    }

    /**
     * Cancel an interview.
     */
    public function cancel(Interview $interview): JsonResponse
    {
        $this->authorize('delete', $interview);

        $cancelled = $this->interviewService->cancel($interview);

        return response()->json([
            'success' => true,
            'message' => 'Interview cancelled successfully.',
            'data' => new InterviewResource($cancelled),
        ]);
    }
}
