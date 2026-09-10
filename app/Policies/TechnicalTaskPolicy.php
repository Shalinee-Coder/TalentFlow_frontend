<?php

namespace App\Policies;

use App\Models\TechnicalTask;
use App\Models\User;

class TechnicalTaskPolicy
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

    public function view(User $user, TechnicalTask $task): bool
    {
        if ($user->isCandidate()) {
            return $user->candidate?->id === $task->application?->candidate_id;
        }

        if ($user->isRecruiter()) {
            return $user->id === $task->recruiter_id ||
                $user->id === $task->application?->job?->recruiter_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isRecruiter();
    }

    public function update(User $user, TechnicalTask $task): bool
    {
        return $user->isRecruiter() && ($user->id === $task->recruiter_id ||
            $user->id === $task->application?->job?->recruiter_id);
    }

    public function updateStatus(User $user, TechnicalTask $task): bool
    {
        if ($user->isCandidate()) {
            return $user->candidate?->id === $task->application?->candidate_id;
        }

        return $this->update($user, $task);
    }

    public function submit(User $user, TechnicalTask $task): bool
    {
        return $user->isCandidate() && $user->candidate?->id === $task->application?->candidate_id;
    }
}
