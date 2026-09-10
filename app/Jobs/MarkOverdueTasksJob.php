<?php

namespace App\Jobs;

use App\Enums\TechnicalTaskStatus;
use App\Events\TechnicalTaskOverdue;
use App\Models\TechnicalTask;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class MarkOverdueTasksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $now = Carbon::now();

        $overdueTasks = TechnicalTask::with(['application.candidate.user', 'recruiter'])
            ->whereIn('status', [TechnicalTaskStatus::PENDING->value, TechnicalTaskStatus::IN_PROGRESS->value])
            ->where('due_at', '<', $now)
            ->get();

        Log::info("MarkOverdueTasksJob: Found {$overdueTasks->count()} overdue tasks to update.");

        foreach ($overdueTasks as $task) {
            $task->update([
                'status' => TechnicalTaskStatus::OVERDUE->value,
            ]);

            event(new TechnicalTaskOverdue($task));

            Log::info("MarkOverdueTasksJob: Marked Task #{$task->id} as Overdue.");
        }
    }
}
