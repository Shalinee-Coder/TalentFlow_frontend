<?php

namespace App\Services;

use App\Enums\TaskSubmissionStatus;
use App\Enums\TechnicalTaskStatus;
use App\Events\TechnicalTaskAssigned;
use App\Events\TechnicalTaskSubmitted;
use App\Models\Application;
use App\Models\TaskSubmission;
use App\Models\TechnicalTask;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TechnicalTaskService
{
    /**
     * Recruiter assigns a technical task to an applicant.
     */
    public function assignTask(Application $application, array $data, User $recruiter): TechnicalTask
    {
        return DB::transaction(function () use ($application, $data, $recruiter) {
            $task = TechnicalTask::create([
                'application_id' => $application->id,
                'recruiter_id' => $recruiter->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'instructions' => $data['instructions'] ?? null,
                'assigned_at' => Carbon::now(),
                'due_at' => Carbon::parse($data['due_at']),
                'status' => TechnicalTaskStatus::PENDING->value,
            ]);

            event(new TechnicalTaskAssigned($task->load(['application.candidate.user', 'recruiter'])));

            return $task;
        });
    }

    /**
     * Candidate submits a technical task.
     */
    public function submitTask(TechnicalTask $task, array $data, User $candidateUser): TaskSubmission
    {
        // 1. Verify task belongs to candidate
        $candidate = $candidateUser->candidate;
        if (!$candidate || $task->application?->candidate_id !== $candidate->id) {
            throw ValidationException::withMessages([
                'task' => ['You are not authorized to submit this technical task.'],
            ]);
        }

        // 2. Check task status: cannot submit if already reviewed
        if ($task->status === TechnicalTaskStatus::REVIEWED) {
            throw ValidationException::withMessages([
                'task' => ['This technical task has already been reviewed and closed.'],
            ]);
        }

        // 3. Check deadline
        if ($task->due_at->isPast()) {
            $task->update(['status' => TechnicalTaskStatus::OVERDUE->value]);
            throw ValidationException::withMessages([
                'task' => ['The deadline for this technical task has passed.'],
            ]);
        }

        return DB::transaction(function () use ($task, $candidate, $data) {
            $submission = TaskSubmission::create([
                'technical_task_id' => $task->id,
                'candidate_id' => $candidate->id,
                'submission_content' => $data['submission_content'] ?? null,
                'submission_url' => $data['submission_url'],
                'submitted_at' => Carbon::now(),
                'status' => TaskSubmissionStatus::SUBMITTED->value,
            ]);

            $task->update([
                'status' => TechnicalTaskStatus::SUBMITTED->value,
            ]);

            event(new TechnicalTaskSubmitted($submission->load(['task.recruiter', 'candidate.user'])));

            return $submission;
        });
    }

    /**
     * Recruiter reviews and grades a task submission.
     */
    public function reviewSubmission(TaskSubmission $submission, array $data, User $reviewer): TaskSubmission
    {
        return DB::transaction(function () use ($submission, $data, $reviewer) {
            $submission->update([
                'status' => $data['status'],
                'review_notes' => $data['review_notes'] ?? null,
                'reviewed_by' => $reviewer->id,
                'reviewed_at' => Carbon::now(),
            ]);

            $submission->task?->update([
                'status' => TechnicalTaskStatus::REVIEWED->value,
            ]);

            return $submission->fresh(['task', 'candidate.user', 'reviewer']);
        });
    }

    /**
     * List technical tasks scoped by user role.
     */
    public function listTasks(User $user, array $filters = []): LengthAwarePaginator
    {
        $query = TechnicalTask::with(['application.job', 'application.candidate.user', 'recruiter', 'latestSubmission']);

        if ($user->isCandidate()) {
            $query->whereHas('application', fn($q) => $q->where('candidate_id', $user->candidate?->id ?? 0));
        } elseif ($user->isRecruiter()) {
            $query->where(function ($q) use ($user) {
                $q->where('recruiter_id', $user->id)
                  ->orWhereHas('application.job', fn($subQuery) => $subQuery->where('recruiter_id', $user->id));
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $query->orderBy('due_at', 'asc');

        $perPage = min((int) ($filters['per_page'] ?? 15), 50);

        return $query->paginate($perPage);
    }
}
