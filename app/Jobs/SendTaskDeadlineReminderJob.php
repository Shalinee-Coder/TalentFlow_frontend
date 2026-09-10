<?php

namespace App\Jobs;

use App\Enums\TechnicalTaskStatus;
use App\Models\TechnicalTask;
use App\Notifications\TaskDeadlineReminderNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendTaskDeadlineReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $now = Carbon::now();
        $targetWindowStart = $now->copy()->addHours(23);
        $targetWindowEnd = $now->copy()->addHours(25);

        // Find pending or in_progress tasks due in ~24 hours where reminder has not been sent yet
        $tasks = TechnicalTask::with(['application.candidate.user'])
            ->whereIn('status', [TechnicalTaskStatus::PENDING->value, TechnicalTaskStatus::IN_PROGRESS->value])
            ->whereBetween('due_at', [$targetWindowStart, $targetWindowEnd])
            ->whereNull('reminder_sent_at')
            ->get();

        Log::info("SendTaskDeadlineReminderJob: Found {$tasks->count()} tasks eligible for 24h reminder.");

        foreach ($tasks as $task) {
            $candidateUser = $task->application?->candidate?->user;
            if ($candidateUser) {
                $candidateUser->notify(new TaskDeadlineReminderNotification($task));
                $task->update(['reminder_sent_at' => Carbon::now()]);
                Log::info("SendTaskDeadlineReminderJob: Sent reminder for Task #{$task->id} to {$candidateUser->email}");
            }
        }
    }
}
