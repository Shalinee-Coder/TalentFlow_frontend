<?php

namespace App\Notifications;

use App\Models\TaskSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskSubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public TaskSubmission $submission
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Technical Task Submitted',
            'task_id' => $this->submission->technical_task_id,
            'submission_id' => $this->submission->id,
            'candidate_name' => $this->submission->candidate?->user?->name,
            'task_title' => $this->submission->task?->title,
            'submission_url' => $this->submission->submission_url,
            'message' => "Candidate {$this->submission->candidate?->user?->name} submitted technical task '{$this->submission->task?->title}'.",
        ];
    }
}
