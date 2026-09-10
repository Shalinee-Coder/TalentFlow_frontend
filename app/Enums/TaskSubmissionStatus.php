<?php

namespace App\Enums;

enum TaskSubmissionStatus: string
{
    case SUBMITTED = 'submitted';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
