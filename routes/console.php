<?php

use Illuminate\Support\Facades\Schedule;
use App\Jobs\SendTaskDeadlineReminderJob;
use App\Jobs\MarkOverdueTasksJob;
use App\Models\Job;
use App\Enums\JobStatus;

Schedule::job(new SendTaskDeadlineReminderJob)->hourly();
Schedule::job(new MarkOverdueTasksJob)->everyFifteenMinutes();

Schedule::call(function () {
    Job::where('status', JobStatus::PUBLISHED->value)
        ->where('application_deadline', '<', now())
        ->update(['status' => JobStatus::CLOSED->value]);
})->daily()->name('close-expired-jobs');
