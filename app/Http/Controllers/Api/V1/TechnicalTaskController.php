<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\TechnicalTaskStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewSubmissionRequest;
use App\Http\Requests\StoreTechnicalTaskRequest;
use App\Http\Requests\SubmitTaskRequest;
use App\Http\Resources\TaskSubmissionResource;
use App\Http\Resources\TechnicalTaskResource;
use App\Models\Application;
use App\Models\TaskSubmission;
use App\Models\TechnicalTask;
use App\Services\TechnicalTaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\Rule;

class TechnicalTaskController extends Controller
{
    public function __construct(
        protected TechnicalTaskService $taskService
    ) {}

    /**
     * Assign a technical task to an applicant.
     */
    public function store(StoreTechnicalTaskRequest $request, Application $application): JsonResponse
    {
        abort_unless(
            $request->user()->isAdmin() || $request->user()->id === $application->job?->recruiter_id,
            403,
            'You are not authorized to assign a task for this application.'
        );

        $task = $this->taskService->assignTask(
            $application,
            $request->validated(),
            $request->user()
        );

        return response()->json([
            'success' => true,
            'message' => 'Technical task assigned successfully.',
            'data' => new TechnicalTaskResource($task),
        ], 201);
    }

    /**
     * List technical tasks.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $tasks = $this->taskService->listTasks($request->user(), $request->all());
        return TechnicalTaskResource::collection($tasks);
    }

    /**
     * Show technical task details.
     */
    public function show(TechnicalTask $task): JsonResponse
    {
        $this->authorize('view', $task);

        $task->load(['application.job', 'application.candidate.user', 'recruiter', 'submissions.reviewer']);

        return response()->json([
            'success' => true,
            'data' => new TechnicalTaskResource($task),
        ]);
    }

    /**
     * Update task status (e.g., candidate starts working: in_progress).
     */
    public function updateStatus(Request $request, TechnicalTask $task): JsonResponse
    {
        $this->authorize('updateStatus', $task);

        $request->validate([
            'status' => ['required', Rule::enum(TechnicalTaskStatus::class)],
        ]);

        $status = TechnicalTaskStatus::from($request->input('status'));

        if ($request->user()->isCandidate() && $status !== TechnicalTaskStatus::IN_PROGRESS) {
            abort(403, 'Candidates can only start an assigned technical task.');
        }

        $task->update(['status' => $status->value]);

        return response()->json([
            'success' => true,
            'message' => "Task status updated to {$status->value}.",
            'data' => new TechnicalTaskResource($task->fresh()),
        ]);
    }

    /**
     * Candidate submits solution for a technical task.
     */
    public function submit(SubmitTaskRequest $request, TechnicalTask $task): JsonResponse
    {
        $submission = $this->taskService->submitTask(
            $task,
            $request->validated(),
            $request->user()
        );

        return response()->json([
            'success' => true,
            'message' => 'Technical task submitted successfully.',
            'data' => new TaskSubmissionResource($submission),
        ], 201);
    }

    /**
     * View submissions for a task.
     */
    public function submissions(TechnicalTask $task): AnonymousResourceCollection
    {
        $this->authorize('view', $task);

        $submissions = $task->submissions()->with(['candidate.user', 'reviewer'])->get();
        return TaskSubmissionResource::collection($submissions);
    }

    /**
     * Recruiter reviews a submission.
     */
    public function review(ReviewSubmissionRequest $request, TaskSubmission $submission): JsonResponse
    {
        $reviewed = $this->taskService->reviewSubmission(
            $submission,
            $request->validated(),
            $request->user()
        );

        return response()->json([
            'success' => true,
            'message' => 'Task submission reviewed successfully.',
            'data' => new TaskSubmissionResource($reviewed),
        ]);
    }
}
