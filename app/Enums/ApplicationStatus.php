<?php

namespace App\Enums;

enum ApplicationStatus: string
{
    case APPLIED = 'applied';
    case SCREENING = 'screening';
    case SHORTLISTED = 'shortlisted';
    case INTERVIEW = 'interview';
    case TECHNICAL_TASK = 'technical_task';
    case HIRED = 'hired';
    case REJECTED = 'rejected';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get the allowed next statuses from current status.
     *
     * Pipeline flow:
     * Applied -> Screening / Rejected
     * Screening -> Shortlisted / Rejected
     * Shortlisted -> Interview / Rejected
     * Interview -> Technical Task / Shortlisted / Rejected
     * Technical Task -> Hired / Rejected
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::APPLIED => [self::SCREENING, self::REJECTED],
            self::SCREENING => [self::SHORTLISTED, self::REJECTED],
            self::SHORTLISTED => [self::INTERVIEW, self::REJECTED],
            self::INTERVIEW => [self::TECHNICAL_TASK, self::SHORTLISTED, self::REJECTED],
            self::TECHNICAL_TASK => [self::HIRED, self::REJECTED],
            self::HIRED => [],
            self::REJECTED => [],
        };
    }

    public function canTransitionTo(self $target): bool
    {
        return in_array($target, $this->allowedTransitions(), true);
    }
}
