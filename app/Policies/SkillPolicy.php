<?php

namespace App\Policies;

use App\Models\Skill;
use App\Models\User;

class SkillPolicy
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

    public function view(User $user, Skill $skill): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isRecruiter();
    }

    public function update(User $user, Skill $skill): bool
    {
        return $user->isRecruiter();
    }

    public function delete(User $user, Skill $skill): bool
    {
        return false; // Only Admin (handled in before())
    }
}
