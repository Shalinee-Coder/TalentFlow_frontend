<?php

namespace App\Notifications;

use App\Enums\ApplicationStatus;
use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ApplicationStatusUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Application $application,
        public ApplicationStatus $toStatus,
        public ?string $remarks = null
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Application Status Update',
            'application_id' => $this->application->id,
            'job_title' => $this->application->job?->title,
            'status' => $this->toStatus->value,
            'remarks' => $this->remarks,
            'message' => "Your application for {$this->application->job?->title} has moved to {$this->toStatus->value}.",
        ];
    }
}
