<?php

namespace App\Events;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Application $application,
        public ApplicationStatus $fromStatus,
        public ApplicationStatus $toStatus,
        public User $changedBy,
        public ?string $remarks = null
    ) {}
}
