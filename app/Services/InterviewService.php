<?php

namespace App\Services;

use App\Enums\InterviewStatus;
use App\Events\InterviewCancelled;
use App\Events\InterviewScheduled;
use App\Models\Application;
use App\Models\Interview;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InterviewService
{
    /**
     * Schedule a new interview with strict overlap conflict detection.
     */
    public function schedule(Application $application, array $data, User $actor): Interview
    {
        $interviewerId = $data['interviewer_id'] ?? $actor->id;
        $scheduledAt = Carbon::parse($data['scheduled_at']);
        $duration = (int) $data['duration'];

        // Validate conflict
        $this->assertNoConflict($interviewerId, $scheduledAt, $duration);

        return DB::transaction(function () use ($application, $interviewerId, $scheduledAt, $duration, $data) {
            $interview = Interview::create([
                'application_id' => $application->id,
                'interviewer_id' => $interviewerId,
                'scheduled_at' => $scheduledAt,
                'duration' => $duration,
                'meeting_link' => $data['meeting_link'] ?? null,
                'status' => InterviewStatus::SCHEDULED->value,
                'notes' => $data['notes'] ?? null,
            ]);

            event(new InterviewScheduled($interview->load(['application.candidate.user', 'application.job', 'interviewer'])));

            return $interview;
        });
    }

    /**
     * Reschedule / update an existing interview.
     */
    public function update(Interview $interview, array $data): Interview
    {
        $interviewerId = $data['interviewer_id'] ?? $interview->interviewer_id;
        $scheduledAt = isset($data['scheduled_at']) ? Carbon::parse($data['scheduled_at']) : $interview->scheduled_at;
        $duration = isset($data['duration']) ? (int) $data['duration'] : $interview->duration;

        // Validate conflict if schedule or interviewer changes
        if ($interviewerId !== $interview->interviewer_id || $scheduledAt->ne($interview->scheduled_at) || $duration !== $interview->duration) {
            $this->assertNoConflict($interviewerId, $scheduledAt, $duration, $interview->id);
        }

        $interview->update(array_filter([
            'interviewer_id' => $interviewerId,
            'scheduled_at' => $scheduledAt,
            'duration' => $duration,
            'meeting_link' => $data['meeting_link'] ?? $interview->meeting_link,
            'status' => $data['status'] ?? $interview->status,
            'notes' => $data['notes'] ?? $interview->notes,
        ], fn($v) => $v !== null));

        return $interview->fresh(['application.candidate.user', 'application.job', 'interviewer']);
    }

    /**
     * Cancel an interview.
     */
    public function cancel(Interview $interview): Interview
    {
        $interview->update([
            'status' => InterviewStatus::CANCELLED->value,
        ]);

        event(new InterviewCancelled($interview->load(['application.candidate.user', 'application.job', 'interviewer'])));

        return $interview;
    }

    /**
     * List interviews scoped by user role.
     */
    public function listInterviews(User $user, array $filters = []): LengthAwarePaginator
    {
        $query = Interview::with(['application.job', 'application.candidate.user', 'interviewer']);
        $query->where('status', '!=', InterviewStatus::CANCELLED->value);

        if ($user->isCandidate()) {
            $query->whereHas('application', fn($q) => $q->where('candidate_id', $user->candidate?->id ?? 0));
        } elseif ($user->isRecruiter()) {
            $query->where(function ($q) use ($user) {
                $q->where('interviewer_id', $user->id)
                  ->orWhereHas('application.job', fn($subQuery) => $subQuery->where('recruiter_id', $user->id));
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['from_date'])) {
            $query->where('scheduled_at', '>=', Carbon::parse($filters['from_date']));
        }

        $query->orderBy('scheduled_at', 'asc');

        $perPage = min((int) ($filters['per_page'] ?? 15), 50);

        return $query->paginate($perPage);
    }

    /**
     * Conflict validation: Checks whether an interviewer has an overlapping interview.
     *
     * Overlap rule:
     * Interview 1: [S1, E1) where E1 = S1 + D1
     * Interview 2: [S2, E2) where E2 = S2 + D2
     * Overlap occurs IF: S1 < E2 AND E1 > S2
     * Back-to-back: S1 == E2 or E1 == S2 is ALLOWED.
     */
    public function assertNoConflict(int $interviewerId, Carbon $startAt, int $durationMinutes, ?int $excludeInterviewId = null): void
    {
        $endAt = $startAt->copy()->addMinutes($durationMinutes);

        // Fetch non-cancelled interviews for this interviewer
        $existingInterviews = Interview::where('interviewer_id', $interviewerId)
            ->where('status', '!=', InterviewStatus::CANCELLED->value)
            ->when($excludeInterviewId, fn($q) => $q->where('id', '!=', $excludeInterviewId))
            ->get();

        foreach ($existingInterviews as $existing) {
            $existingStart = Carbon::parse($existing->scheduled_at);
            $existingEnd = $existingStart->copy()->addMinutes($existing->duration);

            // Check if intervals overlap: start < existingEnd AND end > existingStart
            if ($startAt->lt($existingEnd) && $endAt->gt($existingStart)) {
                $conflictStart = $existingStart->format('Y-m-d H:i');
                $conflictEnd = $existingEnd->format('H:i');

                throw ValidationException::withMessages([
                    'scheduled_at' => [
                        "Scheduling conflict detected. The interviewer already has an active interview scheduled from {$conflictStart} to {$conflictEnd} UTC."
                    ],
                ]);
            }
        }
    }
}
