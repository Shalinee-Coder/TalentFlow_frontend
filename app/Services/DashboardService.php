<?php

namespace App\Services;

use App\Enums\ApplicationStatus;
use App\Enums\InterviewStatus;
use App\Models\Application;
use App\Models\Interview;
use App\Models\Job;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function getMonthlyActivity(User $user): array
    {
        $months = [];
        $monthStart = Carbon::now()->startOfMonth()->subMonths(5);

        for ($index = 0; $index < 6; $index++) {
            $start = $monthStart->copy()->addMonths($index)->startOfMonth();
            $end = $start->copy()->endOfMonth();

            $applicationQuery = Application::whereBetween('applied_at', [$start, $end]);
            $interviewQuery = Interview::whereBetween('scheduled_at', [$start, $end])
                ->where('status', '!=', InterviewStatus::CANCELLED->value);

            if ($user->isRecruiter()) {
                $applicationQuery->whereHas('job', fn($query) => $query->where('recruiter_id', $user->id));
                $interviewQuery->where(function ($query) use ($user) {
                    $query->where('interviewer_id', $user->id)
                        ->orWhereHas('application.job', fn($subQuery) => $subQuery->where('recruiter_id', $user->id));
                });
            } elseif ($user->isCandidate()) {
                $applicationQuery->where('candidate_id', $user->candidate?->id ?? 0);
                $interviewQuery->whereHas('application', fn($query) => $query->where('candidate_id', $user->candidate?->id ?? 0));
            }

            $months[] = [
                'key' => $start->format('Y-m'),
                'label' => $start->format('M'),
                'applications' => $applicationQuery->count(),
                'interviews' => $interviewQuery->count(),
            ];
        }

        return $months;
    }

    /**
     * Get high-level recruitment overview KPIs.
     */
    public function getOverview(User $user, string $period = 'all'): array
    {
        $periodStart = match ($period) {
            'week' => Carbon::now()->startOfWeek(),
            'month' => Carbon::now()->startOfMonth(),
            default => null,
        };
        $isRecruiter = $user->isRecruiter();
        $isCandidate = $user->isCandidate();
        $recruiterId = $user->id;

        // Total jobs
        $jobQuery = Job::query();
        if ($periodStart) {
            $jobQuery->where('created_at', '>=', $periodStart);
        }
        if ($isRecruiter) {
            $jobQuery->where('recruiter_id', $recruiterId);
        } elseif ($isCandidate) {
            $jobQuery->where('status', 'published')->where('application_deadline', '>=', now());
        }
        $totalJobs = $jobQuery->count();
        $publishedJobs = (clone $jobQuery)->where('status', 'published')->count();

        // Applications query
        $appQuery = Application::query();
        if ($periodStart) {
            $appQuery->where('applied_at', '>=', $periodStart);
        }
        if ($isRecruiter) {
            $appQuery->whereHas('job', fn($q) => $q->where('recruiter_id', $recruiterId));
        } elseif ($isCandidate) {
            $appQuery->where('candidate_id', $user->candidate?->id ?? 0);
        }
        $totalApplications = $appQuery->count();
        $shortlistedApplications = (clone $appQuery)
            ->where('current_status', ApplicationStatus::SHORTLISTED->value)
            ->count();

        // Active Candidates (distinct candidates not rejected or hired yet)
        $activeCandidates = (clone $appQuery)
            ->whereNotIn('current_status', [ApplicationStatus::REJECTED->value, ApplicationStatus::HIRED->value])
            ->distinct('candidate_id')
            ->count('candidate_id');

        // Average candidate score
        $averageScore = round((float) (clone $appQuery)->avg('score') ?: 0.0, 2);

        // Interviews in the selected reporting period.
        $interviewQuery = Interview::query()
            ->where('status', '!=', InterviewStatus::CANCELLED->value);

        if ($periodStart) {
            $interviewQuery->where('scheduled_at', '>=', $periodStart);
        }

        if ($isRecruiter) {
            $interviewQuery->where(function ($q) use ($recruiterId) {
                $q->where('interviewer_id', $recruiterId)
                  ->orWhereHas('application.job', fn($sub) => $sub->where('recruiter_id', $recruiterId));
            });
        } elseif ($isCandidate) {
            $interviewQuery->whereHas('application', fn($q) => $q->where('candidate_id', $user->candidate?->id ?? 0));
        }
        $interviewsInPeriod = $interviewQuery->count();
        $scheduledInterviews = (clone $interviewQuery)
            ->where('status', InterviewStatus::SCHEDULED->value)
            ->count();

        $pipelineCounts = (clone $appQuery)
            ->select('current_status', DB::raw('count(*) as count'))
            ->groupBy('current_status')
            ->pluck('count', 'current_status')
            ->toArray();

        return [
            'total_jobs' => $totalJobs,
            'published_jobs' => $publishedJobs,
            'total_applications' => $totalApplications,
            'shortlisted_applications' => $shortlistedApplications,
            'active_candidates' => $activeCandidates,
            'interviews_this_week' => $interviewsInPeriod,
            'interviews_in_period' => $interviewsInPeriod,
            'scheduled_interviews' => $scheduledInterviews,
            'pipeline_counts' => $pipelineCounts,
            'average_candidate_score' => $averageScore,
        ];
    }

    /**
     * Get distribution of applications across the hiring pipeline.
     */
    public function getPipelineDistribution(User $user): array
    {
        $isRecruiter = $user->isRecruiter();
        $isCandidate = $user->isCandidate();
        $recruiterId = $user->id;

        $appQuery = Application::query();
        if ($isRecruiter) {
            $appQuery->whereHas('job', fn($q) => $q->where('recruiter_id', $recruiterId));
        } elseif ($isCandidate) {
            $appQuery->where('candidate_id', $user->candidate?->id ?? 0);
        }

        $rawCounts = (clone $appQuery)
            ->select('current_status', DB::raw('count(*) as count'))
            ->groupBy('current_status')
            ->pluck('count', 'current_status')
            ->toArray();

        // Guarantee all enum stages are present in the response map
        $distribution = [];
        foreach (ApplicationStatus::cases() as $statusCase) {
            $displayName = match ($statusCase) {
                ApplicationStatus::APPLIED => 'Applied',
                ApplicationStatus::SCREENING => 'Screening',
                ApplicationStatus::SHORTLISTED => 'Shortlisted',
                ApplicationStatus::INTERVIEW => 'Interview',
                ApplicationStatus::TECHNICAL_TASK => 'Technical Task',
                ApplicationStatus::HIRED => 'Hired',
                ApplicationStatus::REJECTED => 'Rejected',
            };

            $distribution[$displayName] = $rawCounts[$statusCase->value] ?? 0;
        }

        return $distribution;
    }

    /**
     * Get interview schedule analytics for current week.
     */
    public function getWeeklyInterviewSchedule(User $user): array
    {
        $isRecruiter = $user->isRecruiter();
        $recruiterId = $user->id;

        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();

        $query = Interview::with(['application.job', 'application.candidate.user'])
            ->whereBetween('scheduled_at', [$startOfWeek, $endOfWeek])
            ->orderBy('scheduled_at', 'asc');

        if ($isRecruiter) {
            $query->where(function ($q) use ($recruiterId) {
                $q->where('interviewer_id', $recruiterId)
                  ->orWhereHas('application.job', fn($sub) => $sub->where('recruiter_id', $recruiterId));
            });
        } elseif ($user->isCandidate()) {
            $query->whereHas('application', fn($q) => $q->where('candidate_id', $user->candidate?->id ?? 0));
        }

        $interviews = $query->get();

        return [
            'week_range' => [
                'start' => $startOfWeek->toDateString(),
                'end' => $endOfWeek->toDateString(),
            ],
            'total_scheduled' => $interviews->count(),
            'interviews' => \App\Http\Resources\InterviewResource::collection($interviews),
        ];
    }
}
