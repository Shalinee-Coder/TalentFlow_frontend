<?php

namespace App\Policies;

use App\Models\Application;
use App\Models\User;

class ApplicationPolicy
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

    public function view(User $user, Application $application): bool
    {
        if ($user->isCandidate()) {
            return $user->candidate?->id === $application->candidate_id;
        }

        if ($user->isRecruiter()) {
            return $user->id === $application->job?->recruiter_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isCandidate();
    }

    public function updateStatus(User $user, Application $application): bool
    {
        if ($user->isRecruiter()) {
            return $user->id === $application->job?->recruiter_id;
        }

        return false;
    }

    public function viewHistory(User $user, Application $application): bool
    {
        return $this->view($user, $application);
    }
}
