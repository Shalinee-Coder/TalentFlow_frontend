<?php

namespace App\Listeners;

use App\Events\ApplicationStatusChanged;
use App\Events\ApplicationSubmitted;
use App\Notifications\ApplicationStatusUpdatedNotification;
use App\Notifications\ApplicationSubmittedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendApplicationNotifications implements ShouldQueue
{
    public function handleApplicationSubmitted(ApplicationSubmitted $event): void
    {
        $recruiter = $event->application->job?->recruiter;
        if ($recruiter) {
            $recruiter->notify(new ApplicationSubmittedNotification($event->application));
        }
    }

    public function handleApplicationStatusChanged(ApplicationStatusChanged $event): void
    {
        $candidateUser = $event->application->candidate?->user;
        if ($candidateUser) {
            $candidateUser->notify(new ApplicationStatusUpdatedNotification(
                $event->application,
                $event->toStatus,
                $event->remarks
            ));
        }
    }
}
