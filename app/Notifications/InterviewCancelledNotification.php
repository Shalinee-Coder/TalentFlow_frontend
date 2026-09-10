<?php

namespace App\Notifications;

use App\Models\Interview;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class InterviewCancelledNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Interview $interview
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Interview Cancelled',
            'interview_id' => $this->interview->id,
            'application_id' => $this->interview->application_id,
            'job_title' => $this->interview->application?->job?->title,
            'scheduled_at' => $this->interview->scheduled_at->toIso8601String(),
            'message' => "The interview previously scheduled for {$this->interview->scheduled_at->format('Y-m-d H:i')} has been cancelled.",
        ];
    }
}
