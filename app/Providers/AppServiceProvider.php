<?php

namespace App\Providers;

use App\Events\ApplicationStatusChanged;
use App\Events\ApplicationSubmitted;
use App\Events\InterviewCancelled;
use App\Events\InterviewScheduled;
use App\Events\TechnicalTaskAssigned;
use App\Events\TechnicalTaskOverdue;
use App\Events\TechnicalTaskSubmitted;

use App\Listeners\SendApplicationNotifications;
use App\Listeners\SendInterviewNotifications;
use App\Listeners\SendTaskNotifications;

use App\Models\Application;
use App\Models\Interview;
use App\Models\Job;
use App\Models\Resume;
use App\Models\Skill;
use App\Models\TaskSubmission;
use App\Models\TechnicalTask;

use App\Policies\ApplicationPolicy;
use App\Policies\InterviewPolicy;
use App\Policies\JobPolicy;
use App\Policies\ResumePolicy;
use App\Policies\SkillPolicy;
use App\Policies\TaskSubmissionPolicy;
use App\Policies\TechnicalTaskPolicy;

use App\Services\Contracts\ResumeParserInterface;
use App\Services\PdfResumeParser;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            ResumeParserInterface::class,
            PdfResumeParser::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Fix MySQL key length issue
        Schema::defaultStringLength(191);

        // Register Authorization Policies
        Gate::policy(Job::class, JobPolicy::class);
        Gate::policy(Skill::class, SkillPolicy::class);
        Gate::policy(Resume::class, ResumePolicy::class);
        Gate::policy(Application::class, ApplicationPolicy::class);
        Gate::policy(Interview::class, InterviewPolicy::class);
        Gate::policy(TechnicalTask::class, TechnicalTaskPolicy::class);
        Gate::policy(TaskSubmission::class, TaskSubmissionPolicy::class);

        // Register Event & Listener Mappings
        Event::listen(
            ApplicationSubmitted::class,
            [SendApplicationNotifications::class, 'handleApplicationSubmitted']
        );

        Event::listen(
            ApplicationStatusChanged::class,
            [SendApplicationNotifications::class, 'handleApplicationStatusChanged']
        );

        Event::listen(
            InterviewScheduled::class,
            [SendInterviewNotifications::class, 'handleInterviewScheduled']
        );

        Event::listen(
            InterviewCancelled::class,
            [SendInterviewNotifications::class, 'handleInterviewCancelled']
        );

        Event::listen(
            TechnicalTaskAssigned::class,
            [SendTaskNotifications::class, 'handleTaskAssigned']
        );

        Event::listen(
            TechnicalTaskSubmitted::class,
            [SendTaskNotifications::class, 'handleTaskSubmitted']
        );

        Event::listen(
            TechnicalTaskOverdue::class,
            [SendTaskNotifications::class, 'handleTaskOverdue']
        );
    }
}