<?php

namespace App\Notifications;

use App\Models\TechnicalTask;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskDeadlineReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        public TechnicalTask $task
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Reminder: Technical Task Deadline in 24 Hours',
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'due_at' => $this->task->due_at->toIso8601String(),
            'message' => "Your technical task '{$this->task->title}' is due in 24 hours ({$this->task->due_at->format('Y-m-d H:i')} UTC). Please submit before the deadline.",
        ];
    }
}
