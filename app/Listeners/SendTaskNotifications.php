<?php

namespace App\Listeners;

use App\Events\TechnicalTaskAssigned;
use App\Events\TechnicalTaskOverdue;
use App\Events\TechnicalTaskSubmitted;
use App\Notifications\TaskAssignedNotification;
use App\Notifications\TaskOverdueNotification;
use App\Notifications\TaskSubmittedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTaskNotifications implements ShouldQueue
{
    public function handleTaskAssigned(TechnicalTaskAssigned $event): void
    {
        $candidateUser = $event->task->application?->candidate?->user;
        if ($candidateUser) {
            $candidateUser->notify(new TaskAssignedNotification($event->task));
        }
    }

    public function handleTaskSubmitted(TechnicalTaskSubmitted $event): void
    {
        $recruiter = $event->submission->task?->recruiter;
        if ($recruiter) {
            $recruiter->notify(new TaskSubmittedNotification($event->submission));
        }
    }

    public function handleTaskOverdue(TechnicalTaskOverdue $event): void
    {
        $candidateUser = $event->task->application?->candidate?->user;
        $recruiter = $event->task->recruiter;

        if ($candidateUser) {
            $candidateUser->notify(new TaskOverdueNotification($event->task));
        }
        if ($recruiter) {
            $recruiter->notify(new TaskOverdueNotification($event->task));
        }
    }
}
