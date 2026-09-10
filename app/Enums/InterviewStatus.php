<?php

namespace App\Enums;

enum InterviewStatus: string
{
    case SCHEDULED = 'scheduled';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case RESCHEDULED = 'rescheduled';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
