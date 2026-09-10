<?php

namespace App\Enums;

enum TechnicalTaskStatus: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case SUBMITTED = 'submitted';
    case REVIEWED = 'reviewed';
    case OVERDUE = 'overdue';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
