<?php

namespace App\Policies;

use App\Models\TaskSubmission;
use App\Models\User;

class TaskSubmissionPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        return null;
    }

    public function view(User $user, TaskSubmission $submission): bool
    {
        if ($user->isCandidate()) {
            return $user->candidate?->id === $submission->candidate_id;
        }

        if ($user->isRecruiter()) {
            return $user->id === $submission->task?->recruiter_id ||
                $user->id === $submission->task?->application?->job?->recruiter_id;
        }

        return false;
    }

    public function review(User $user, TaskSubmission $submission): bool
    {
        if ($user->isRecruiter()) {
            return $user->id === $submission->task?->recruiter_id ||
                $user->id === $submission->task?->application?->job?->recruiter_id;
        }

        return false;
    }
}
