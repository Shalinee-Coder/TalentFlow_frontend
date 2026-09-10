<?php

namespace App\Policies;

use App\Enums\JobStatus;
use App\Models\Job;
use App\Models\User;

class JobPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        return null;
    }

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Job $job): bool
    {
        if ($job->status === JobStatus::PUBLISHED) {
            return true;
        }

        if (!$user) {
            return false;
        }

        return $user->id === $job->recruiter_id;
    }

    public function create(User $user): bool
    {
        return $user->isRecruiter();
    }

    public function update(User $user, Job $job): bool
    {
        return $user->id === $job->recruiter_id;
    }

    public function delete(User $user, Job $job): bool
    {
        return $user->id === $job->recruiter_id;
    }
}
