<?php

namespace App\Enums;

enum JobStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case CLOSED = 'closed';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
