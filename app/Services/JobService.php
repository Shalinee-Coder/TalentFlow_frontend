<?php

namespace App\Services;

use App\Enums\JobStatus;
use App\Models\Job;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class JobService
{
    /**
     * Get paginated jobs with filters and eager loading.
     */
    public function listJobs(array $filters = [], ?User $user = null): LengthAwarePaginator
    {
        $query = Job::with(['recruiter', 'skills'])->withCount('applications');

        // Access scope: Unauthenticated or candidates only see published jobs
        if (!$user || $user->isCandidate()) {
            $query->where('status', JobStatus::PUBLISHED->value)
                ->where('application_deadline', '>=', now());
        } elseif ($user->isRecruiter()) {
            // Optional: If recruiter wants only their jobs or all published + their drafts
            if (!empty($filters['my_jobs'])) {
                $query->where('recruiter_id', $user->id);
            }
        }

        // Status filter (for recruiters / admins)
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // Department filter
        if (!empty($filters['department'])) {
            $query->where('department', 'like', '%' . $filters['department'] . '%');
        }

        // Experience filter
        if (isset($filters['max_experience'])) {
            $query->where('required_experience', '<=', $filters['max_experience']);
        }

        // Search in title or description
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sort = $filters['sort'] ?? 'latest';
        match ($sort) {
            'deadline' => $query->orderBy('application_deadline', 'asc'),
            'oldest' => $query->orderBy('created_at', 'asc'),
            default => $query->orderBy('created_at', 'desc'),
        };

        $perPage = min((int) ($filters['per_page'] ?? 15), 50);

        return $query->paginate($perPage);
    }

    /**
     * Create a new job with skills.
     */
    public function createJob(User $recruiter, array $data): Job
    {
        return DB::transaction(function () use ($recruiter, $data) {
            $job = Job::create([
                'recruiter_id' => $recruiter->id,
                'title' => $data['title'],
                'department' => $data['department'],
                'description' => $data['description'],
                'required_experience' => $data['required_experience'],
                'salary_min' => $data['salary_min'] ?? null,
                'salary_max' => $data['salary_max'] ?? null,
                'application_deadline' => $data['application_deadline'],
                'status' => $data['status'] ?? JobStatus::DRAFT->value,
            ]);

            if (!empty($data['skills'])) {
                $syncData = [];
                foreach ($data['skills'] as $skillItem) {
                    $syncData[$skillItem['skill_id']] = [
                        'is_required' => $skillItem['is_required'] ?? true,
                        'weight' => $skillItem['weight'] ?? 1,
                    ];
                }
                $job->skills()->sync($syncData);
            }

            return $job->load(['recruiter', 'skills']);
        });
    }

    /**
     * Update job details and skills.
     */
    public function updateJob(Job $job, array $data): Job
    {
        return DB::transaction(function () use ($job, $data) {
            $job->update(array_filter([
                'title' => $data['title'] ?? $job->title,
                'department' => $data['department'] ?? $job->department,
                'description' => $data['description'] ?? $job->description,
                'required_experience' => $data['required_experience'] ?? $job->required_experience,
                'salary_min' => array_key_exists('salary_min', $data) ? $data['salary_min'] : $job->salary_min,
                'salary_max' => array_key_exists('salary_max', $data) ? $data['salary_max'] : $job->salary_max,
                'application_deadline' => $data['application_deadline'] ?? $job->application_deadline,
                'status' => $data['status'] ?? $job->status,
            ], fn($val) => $val !== null));

            if (isset($data['skills'])) {
                $syncData = [];
                foreach ($data['skills'] as $skillItem) {
                    $syncData[$skillItem['skill_id']] = [
                        'is_required' => $skillItem['is_required'] ?? true,
                        'weight' => $skillItem['weight'] ?? 1,
                    ];
                }
                $job->skills()->sync($syncData);
            }

            return $job->fresh(['recruiter', 'skills']);
        });
    }

    /**
     * Update job publication status.
     */
    public function updateStatus(Job $job, JobStatus|string $status): Job
    {
        $statusValue = $status instanceof JobStatus ? $status->value : $status;
        $job->update(['status' => $statusValue]);
        return $job->fresh(['recruiter', 'skills']);
    }

    /**
     * Delete a job.
     */
    public function deleteJob(Job $job): void
    {
        $job->delete();
    }
}
