<?php

namespace App\Policies;

use App\Models\Interview;
use App\Models\User;

class InterviewPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Interview $interview): bool
    {
        if ($user->isCandidate()) {
            return $user->candidate?->id === $interview->application?->candidate_id;
        }

        if ($user->isRecruiter()) {
            return $user->id === $interview->interviewer_id ||
                $user->id === $interview->application?->job?->recruiter_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isRecruiter();
    }

    public function update(User $user, Interview $interview): bool
    {
        return $user->id === $interview->interviewer_id ||
            $user->id === $interview->application?->job?->recruiter_id;
    }

    public function delete(User $user, Interview $interview): bool
    {
        return $user->id === $interview->interviewer_id ||
            $user->id === $interview->application?->job?->recruiter_id;
    }
}
