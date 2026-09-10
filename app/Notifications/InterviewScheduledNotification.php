<?php

namespace App\Notifications;

use App\Models\Interview;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class InterviewScheduledNotification extends Notification
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
            'title' => 'Interview Scheduled',
            'interview_id' => $this->interview->id,
            'application_id' => $this->interview->application_id,
            'job_title' => $this->interview->application?->job?->title,
            'scheduled_at' => $this->interview->scheduled_at->toIso8601String(),
            'duration' => $this->interview->duration,
            'meeting_link' => $this->interview->meeting_link,
            'message' => "An interview for {$this->interview->application?->job?->title} is scheduled on {$this->interview->scheduled_at->format('Y-m-d H:i')} UTC.",
        ];
    }
}
