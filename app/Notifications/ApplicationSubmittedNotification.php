<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ApplicationSubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Application $application
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Job Application Received',
            'application_id' => $this->application->id,
            'job_id' => $this->application->job_id,
            'job_title' => $this->application->job?->title,
            'candidate_name' => $this->application->candidate?->user?->name,
            'candidate_email' => $this->application->candidate?->user?->email,
            'score' => (float) $this->application->score,
            'message' => "Candidate {$this->application->candidate?->user?->name} applied for {$this->application->job?->title}.",
        ];
    }
}
