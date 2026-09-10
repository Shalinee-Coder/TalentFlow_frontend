<?php

namespace App\Listeners;

use App\Events\InterviewCancelled;
use App\Events\InterviewScheduled;
use App\Notifications\InterviewCancelledNotification;
use App\Notifications\InterviewScheduledNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendInterviewNotifications implements ShouldQueue
{
    public function handleInterviewScheduled(InterviewScheduled $event): void
    {
        $candidateUser = $event->interview->application?->candidate?->user;
        $interviewer = $event->interview->interviewer;

        if ($candidateUser) {
            $candidateUser->notify(new InterviewScheduledNotification($event->interview));
        }

        if ($interviewer && $interviewer->id !== $event->interview->application?->job?->recruiter_id) {
            $interviewer->notify(new InterviewScheduledNotification($event->interview));
        }
    }

    public function handleInterviewCancelled(InterviewCancelled $event): void
    {
        $candidateUser = $event->interview->application?->candidate?->user;
        $interviewer = $event->interview->interviewer;

        if ($candidateUser) {
            $candidateUser->notify(new InterviewCancelledNotification($event->interview));
        }

        if ($interviewer && $interviewer->id !== $event->interview->application?->job?->recruiter_id) {
            $interviewer->notify(new InterviewCancelledNotification($event->interview));
        }
    }
}
