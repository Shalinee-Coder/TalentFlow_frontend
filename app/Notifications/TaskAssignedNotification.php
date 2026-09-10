<?php

namespace App\Notifications;

use App\Models\TechnicalTask;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskAssignedNotification extends Notification
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
            'title' => 'Technical Task Assigned',
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'due_at' => $this->task->due_at->toIso8601String(),
            'message' => "You have been assigned a technical assessment: '{$this->task->title}'. Due date: {$this->task->due_at->format('Y-m-d H:i')} UTC.",
        ];
    }
}
