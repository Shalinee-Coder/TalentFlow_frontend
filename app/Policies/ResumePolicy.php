<?php

namespace App\Policies;

use App\Models\Resume;
use App\Models\User;

class ResumePolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->isAdmin()) {
            return true;
        }
        return null;
    }

    public function view(User $user, Resume $resume): bool
    {
        // Candidate can view their own resume
        if ($user->isCandidate() && $user->candidate?->id === $resume->candidate_id) {
            return true;
        }

        // Recruiter can view if resume is submitted to one of their jobs
        if ($user->isRecruiter()) {
            return $resume->applications()
                ->whereHas('job', fn($q) => $q->where('recruiter_id', $user->id))
                ->exists();
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isCandidate();
    }

    public function download(User $user, Resume $resume): bool
    {
        return $this->view($user, $resume);
    }
}
