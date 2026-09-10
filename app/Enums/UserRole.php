<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case RECRUITER = 'recruiter';
    case CANDIDATE = 'candidate';

    public function displayName(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator',
            self::RECRUITER => 'Recruiter',
            self::CANDIDATE => 'Candidate',
        };
    }
}
