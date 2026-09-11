<?php

namespace Database\Seeders;

use App\Enums\ApplicationStatus;
use App\Enums\InterviewStatus;
use App\Enums\ResumeStatus;
use App\Enums\TaskSubmissionStatus;
use App\Enums\TechnicalTaskStatus;
use App\Models\Application;
use App\Models\ApplicationStatusHistory;
use App\Models\Candidate;
use App\Models\Interview;
use App\Models\Job;
use App\Models\Resume;
use App\Models\TaskSubmission;
use App\Models\TechnicalTask;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $job1 = Job::where('title', 'Senior Laravel Architect')->first();
        $job2 = Job::where('title', 'Full Stack Engineer (Vue & Laravel)')->first();
        $recruiter1 = User::where('email', 'recruiter1@talentflow.local')->first();

        if (!$job1 || !$recruiter1) {
            return;
        }

        $cand1 = Candidate::whereHas('user', fn($q) => $q->where('email', 'candidate1@talentflow.local'))->first();
        $cand2 = Candidate::whereHas('user', fn($q) => $q->where('email', 'candidate2@talentflow.local'))->first();
        $cand3 = Candidate::whereHas('user', fn($q) => $q->where('email', 'candidate3@talentflow.local'))->first();
        $cand4 = Candidate::whereHas('user', fn($q) => $q->where('email', 'candidate4@talentflow.local'))->first();

        // Resume for Candidate 1: Aarav Sharma
        if ($cand1) {
            $resume1 = Resume::firstOrCreate(
                ['candidate_id' => $cand1->id],
                [
                    'file_path' => 'resumes/' . Str::uuid() . '.pdf',
                    'original_filename' => 'Aarav_Sharma_Senior_Laravel_Resume.pdf',
                    'mime_type' => 'application/pdf',
                    'file_size' => 452100,
                    'processing_status' => ResumeStatus::PROCESSED->value,
                    'extracted_text' => 'Aarav Sharma. Senior Laravel & PHP Architect with 5.5 years of experience in MySQL, Docker, REST API, Redis, and AWS. M.Tech in Computer Science from IIT Bombay. GitHub: https://github.com/aaravsharma',
                    'extracted_data' => [
                        'email' => 'candidate1@talentflow.local',
                        'phone' => '+91-98201-12345',
                        'links' => ['https://github.com/aaravsharma'],
                        'skills' => [
                            ['id' => null, 'name' => 'PHP', 'slug' => 'php'],
                            ['id' => null, 'name' => 'Laravel', 'slug' => 'laravel'],
                            ['id' => null, 'name' => 'MySQL', 'slug' => 'mysql'],
                            ['id' => null, 'name' => 'Docker', 'slug' => 'docker'],
                            ['id' => null, 'name' => 'AWS', 'slug' => 'aws'],
                        ],
                        'experience_years' => 5.5,
                        'education_level' => 'Master',
                    ],
                    'processed_at' => Carbon::now()->subDays(3),
                ]
            );

            // Application 1: Advanced to Technical Task
            $app1 = Application::firstOrCreate(
                ['job_id' => $job1->id, 'candidate_id' => $cand1->id],
                [
                    'resume_id' => $resume1->id,
                    'score' => 95.00,
                    'current_status' => ApplicationStatus::TECHNICAL_TASK->value,
                    'applied_at' => Carbon::now()->subDays(3),
                ]
            );

            // Histories
            ApplicationStatusHistory::firstOrCreate(
                ['application_id' => $app1->id, 'to_status' => ApplicationStatus::APPLIED->value],
                [
                    'from_status' => null,
                    'changed_by' => $cand1->user_id,
                    'changed_at' => Carbon::now()->subDays(3),
                    'remarks' => 'Applied by Aarav Sharma via portal.',
                ]
            );

            ApplicationStatusHistory::firstOrCreate(
                ['application_id' => $app1->id, 'to_status' => ApplicationStatus::SCREENING->value],
                [
                    'from_status' => ApplicationStatus::APPLIED->value,
                    'changed_by' => $recruiter1->id,
                    'changed_at' => Carbon::now()->subDays(2)->addHours(2),
                    'remarks' => 'Exceptional Laravel background at Razorpay. Strong architecture score.',
                ]
            );

            ApplicationStatusHistory::firstOrCreate(
                ['application_id' => $app1->id, 'to_status' => ApplicationStatus::SHORTLISTED->value],
                [
                    'from_status' => ApplicationStatus::SCREENING->value,
                    'changed_by' => $recruiter1->id,
                    'changed_at' => Carbon::now()->subDays(2)->addHours(4),
                    'remarks' => 'Shortlisted for system design round.',
                ]
            );

            ApplicationStatusHistory::firstOrCreate(
                ['application_id' => $app1->id, 'to_status' => ApplicationStatus::INTERVIEW->value],
                [
                    'from_status' => ApplicationStatus::SHORTLISTED->value,
                    'changed_by' => $recruiter1->id,
                    'changed_at' => Carbon::now()->subDay(),
                    'remarks' => 'Technical screen completed with flying colors.',
                ]
            );

            ApplicationStatusHistory::firstOrCreate(
                ['application_id' => $app1->id, 'to_status' => ApplicationStatus::TECHNICAL_TASK->value],
                [
                    'from_status' => ApplicationStatus::INTERVIEW->value,
                    'changed_by' => $recruiter1->id,
                    'changed_at' => Carbon::now()->subHours(6),
                    'remarks' => 'Passed technical interview, assigned high-throughput pipeline task.',
                ]
            );

            // Completed interview with Priya Nair
            Interview::firstOrCreate(
                ['application_id' => $app1->id],
                [
                    'scheduled_at' => Carbon::now()->subDay()->setHour(14)->setMinute(0),
                    'interviewer_id' => $recruiter1->id,
                    'duration' => 60,
                    'meeting_link' => 'https://meet.google.com/aarav-priya-tech-interview',
                    'status' => InterviewStatus::COMPLETED->value,
                    'notes' => 'Aarav demonstrated stellar knowledge of Laravel internals, queue workers, and database sharding.',
                ]
            );

            // Technical Task
            $task = TechnicalTask::firstOrCreate(
                ['application_id' => $app1->id, 'title' => 'Build High-Throughput Queue Pipeline in Laravel'],
                [
                    'recruiter_id' => $recruiter1->id,
                    'description' => 'Implement an idempotent queue worker processing resume parsing requests with backoff retries.',
                    'instructions' => 'Push code to a public GitHub repo with PHPUnit feature tests.',
                    'assigned_at' => Carbon::now()->subHours(6),
                    'due_at' => Carbon::now()->addDays(3),
                    'status' => TechnicalTaskStatus::SUBMITTED->value,
                ]
            );

            // Task Submission
            TaskSubmission::firstOrCreate(
                ['technical_task_id' => $task->id, 'candidate_id' => $cand1->id],
                [
                    'submission_content' => 'Implemented using Laravel Job batches, Redis queues, and comprehensive test suite.',
                    'submission_url' => 'https://github.com/aaravsharma/talentflow-assessment',
                    'submitted_at' => Carbon::now()->subHours(1),
                    'status' => TaskSubmissionStatus::SUBMITTED->value,
                ]
            );

            $task->update(['status' => TechnicalTaskStatus::SUBMITTED->value]);
        }

        // Candidate 2: Ananya Iyer (Screening stage)
        if ($cand2 && $job2) {
            $resume2 = Resume::firstOrCreate(
                ['candidate_id' => $cand2->id],
                [
                    'file_path' => 'resumes/' . Str::uuid() . '.pdf',
                    'original_filename' => 'Ananya_Iyer_FullStack_Resume.pdf',
                    'mime_type' => 'application/pdf',
                    'file_size' => 312000,
                    'processing_status' => ResumeStatus::PROCESSED->value,
                    'extracted_text' => 'Ananya Iyer. 3.2 years experience in Laravel, Vue.js, MySQL, and TailwindCSS at Swiggy. B.Tech NIT Trichy.',
                    'extracted_data' => [
                        'email' => 'candidate2@talentflow.local',
                        'phone' => '+91-98450-23456',
                        'skills' => [
                            ['id' => null, 'name' => 'Laravel', 'slug' => 'laravel'],
                            ['id' => null, 'name' => 'Vue', 'slug' => 'vue'],
                            ['id' => null, 'name' => 'MySQL', 'slug' => 'mysql'],
                        ],
                        'experience_years' => 3.2,
                        'education_level' => 'Bachelor',
                    ],
                    'processed_at' => Carbon::now()->subDay(),
                ]
            );

            $app2 = Application::firstOrCreate(
                ['job_id' => $job2->id, 'candidate_id' => $cand2->id],
                [
                    'resume_id' => $resume2->id,
                    'score' => 86.50,
                    'current_status' => ApplicationStatus::SCREENING->value,
                    'applied_at' => Carbon::now()->subDay(),
                ]
            );

            ApplicationStatusHistory::firstOrCreate(
                ['application_id' => $app2->id, 'to_status' => ApplicationStatus::APPLIED->value],
                [
                    'from_status' => null,
                    'changed_by' => $cand2->user_id,
                    'changed_at' => Carbon::now()->subDay(),
                    'remarks' => 'Applied by Ananya Iyer.',
                ]
            );

            ApplicationStatusHistory::firstOrCreate(
                ['application_id' => $app2->id, 'to_status' => ApplicationStatus::SCREENING->value],
                [
                    'from_status' => ApplicationStatus::APPLIED->value,
                    'changed_by' => $recruiter1->id,
                    'changed_at' => Carbon::now()->subHours(8),
                    'remarks' => 'Great Vue + Laravel blend from Swiggy Pune. Advancing to screening.',
                ]
            );

            Interview::firstOrCreate(
                ['application_id' => $app2->id],
                [
                    'scheduled_at' => Carbon::now()->addDay()->setHour(11)->setMinute(30),
                    'interviewer_id' => $recruiter1->id,
                    'duration' => 45,
                    'meeting_link' => 'https://meet.google.com/ananya-vue-screen',
                    'status' => InterviewStatus::SCHEDULED->value,
                    'notes' => 'Screening discussion focused on Vue component design, REST APIs, and product collaboration.',
                ]
            );

            $task2 = TechnicalTask::firstOrCreate(
                ['application_id' => $app2->id, 'title' => 'Build a Vue Candidate Search Panel'],
                [
                    'recruiter_id' => $recruiter1->id,
                    'description' => 'Create a responsive candidate search panel with filters, pagination, and a Laravel API endpoint.',
                    'instructions' => 'Include validation, loading states, and tests for pagination and filtering.',
                    'assigned_at' => Carbon::now()->subHours(3),
                    'due_at' => Carbon::now()->addDays(5),
                    'status' => TechnicalTaskStatus::PENDING->value,
                ]
            );

            $task2->update(['status' => TechnicalTaskStatus::PENDING->value]);
        }

        // Candidate 3: Rohan Verma (Applied stage)
        if ($cand3 && $job1) {
            $resume3 = Resume::firstOrCreate(
                ['candidate_id' => $cand3->id],
                [
                    'file_path' => 'resumes/' . Str::uuid() . '.pdf',
                    'original_filename' => 'Rohan_Verma_Lead_Architect_Resume.pdf',
                    'mime_type' => 'application/pdf',
                    'file_size' => 520000,
                    'processing_status' => ResumeStatus::PROCESSED->value,
                    'extracted_text' => 'Rohan Verma. 7 years experience in PHP, Laravel, Docker, MySQL, Redis at Flipkart. B.E. BITS Pilani.',
                    'extracted_data' => [
                        'email' => 'candidate3@talentflow.local',
                        'phone' => '+91-98110-34567',
                        'skills' => [
                            ['id' => null, 'name' => 'PHP', 'slug' => 'php'],
                            ['id' => null, 'name' => 'Laravel', 'slug' => 'laravel'],
                            ['id' => null, 'name' => 'Docker', 'slug' => 'docker'],
                            ['id' => null, 'name' => 'MySQL', 'slug' => 'mysql'],
                        ],
                        'experience_years' => 7.0,
                        'education_level' => 'Bachelor',
                    ],
                    'processed_at' => Carbon::now()->subHours(3),
                ]
            );

            $app3 = Application::firstOrCreate(
                ['job_id' => $job1->id, 'candidate_id' => $cand3->id],
                [
                    'resume_id' => $resume3->id,
                    'score' => 91.00,
                    'current_status' => ApplicationStatus::APPLIED->value,
                    'applied_at' => Carbon::now()->subHours(3),
                ]
            );

            ApplicationStatusHistory::firstOrCreate(
                ['application_id' => $app3->id, 'to_status' => ApplicationStatus::APPLIED->value],
                [
                    'from_status' => null,
                    'changed_by' => $cand3->user_id,
                    'changed_at' => Carbon::now()->subHours(3),
                    'remarks' => 'Applied by Rohan Verma.',
                ]
            );

            Interview::firstOrCreate(
                ['application_id' => $app3->id],
                [
                    'scheduled_at' => Carbon::now()->addDays(2)->setHour(15)->setMinute(0),
                    'interviewer_id' => $recruiter1->id,
                    'duration' => 60,
                    'meeting_link' => 'https://meet.google.com/rohan-architecture-round',
                    'status' => InterviewStatus::SCHEDULED->value,
                    'notes' => 'Architecture round for distributed systems, reliability, and technical leadership.',
                ]
            );

            $task3 = TechnicalTask::firstOrCreate(
                ['application_id' => $app3->id, 'title' => 'Design a Scalable Hiring Notification Service'],
                [
                    'recruiter_id' => $recruiter1->id,
                    'description' => 'Design an event-driven notification service for application, interview, and task updates.',
                    'instructions' => 'Submit an architecture note and a small working Laravel prototype with tests.',
                    'assigned_at' => Carbon::now()->subHour(),
                    'due_at' => Carbon::now()->addDays(7),
                    'status' => TechnicalTaskStatus::PENDING->value,
                ]
            );

            $task3->update(['status' => TechnicalTaskStatus::PENDING->value]);
        }

        // Candidate 4: Sneha Kulkarni (shortlisted stage)
        if ($cand4 && $job2) {
            $resume4 = Resume::firstOrCreate(
                ['candidate_id' => $cand4->id],
                [
                    'file_path' => 'resumes/' . Str::uuid() . '.pdf',
                    'original_filename' => 'Sneha_Kulkarni_Web_Developer_Resume.pdf',
                    'mime_type' => 'application/pdf',
                    'file_size' => 295000,
                    'processing_status' => ResumeStatus::PROCESSED->value,
                    'extracted_text' => 'Sneha Kulkarni. Junior web developer with 2.5 years of experience in Laravel, Vue, JavaScript, and MySQL at Jio Platforms.',
                    'extracted_data' => [
                        'email' => 'candidate4@talentflow.local',
                        'phone' => '+91-98220-45678',
                        'skills' => [
                            ['id' => null, 'name' => 'Laravel', 'slug' => 'laravel'],
                            ['id' => null, 'name' => 'Vue', 'slug' => 'vue'],
                            ['id' => null, 'name' => 'MySQL', 'slug' => 'mysql'],
                        ],
                        'experience_years' => 2.5,
                        'education_level' => 'Bachelor',
                    ],
                    'processed_at' => Carbon::now()->subHours(5),
                ]
            );

            $app4 = Application::firstOrCreate(
                ['job_id' => $job1->id, 'candidate_id' => $cand4->id],
                [
                    'resume_id' => $resume4->id,
                    'score' => 81.00,
                    'current_status' => ApplicationStatus::SHORTLISTED->value,
                    'applied_at' => Carbon::now()->subDays(2),
                ]
            );

            ApplicationStatusHistory::firstOrCreate(
                ['application_id' => $app4->id, 'to_status' => ApplicationStatus::APPLIED->value],
                ['from_status' => null, 'changed_by' => $cand4->user_id, 'changed_at' => Carbon::now()->subDays(2), 'remarks' => 'Applied by Sneha Kulkarni.']
            );
            ApplicationStatusHistory::firstOrCreate(
                ['application_id' => $app4->id, 'to_status' => ApplicationStatus::SCREENING->value],
                ['from_status' => ApplicationStatus::APPLIED->value, 'changed_by' => $recruiter1->id, 'changed_at' => Carbon::now()->subDay(), 'remarks' => 'Strong Vue and Laravel fundamentals identified during screening.']
            );
            ApplicationStatusHistory::firstOrCreate(
                ['application_id' => $app4->id, 'to_status' => ApplicationStatus::SHORTLISTED->value],
                ['from_status' => ApplicationStatus::SCREENING->value, 'changed_by' => $recruiter1->id, 'changed_at' => Carbon::now()->subHours(4), 'remarks' => 'Shortlisted for the product engineering discussion.']
            );
        }

        // Give any newly registered demo candidate a complete, usable workflow.
        if ($job2) {
            Candidate::with('user')->whereDoesntHave('applications')->get()->each(function (Candidate $candidate) use ($job2, $recruiter1): void {
                $resume = Resume::firstOrCreate(
                    ['candidate_id' => $candidate->id],
                    [
                        'file_path' => 'resumes/' . Str::uuid() . '.pdf',
                        'original_filename' => Str::slug($candidate->user?->name ?: 'candidate') . '-resume.pdf',
                        'mime_type' => 'application/pdf',
                        'file_size' => 280000,
                        'processing_status' => ResumeStatus::PROCESSED->value,
                        'extracted_text' => ($candidate->user?->name ?: 'Candidate') . ' profile submitted through the TalentFlow demo portal.',
                        'extracted_data' => [
                            'email' => $candidate->user?->email,
                            'phone' => $candidate->phone,
                            'skills' => [],
                            'experience_years' => (float) ($candidate->total_experience ?: 2),
                            'education_level' => $candidate->highest_education ?: 'Bachelor',
                        ],
                        'processed_at' => Carbon::now(),
                    ]
                );

                $application = Application::firstOrCreate(
                    ['job_id' => $job2->id, 'candidate_id' => $candidate->id],
                    [
                        'resume_id' => $resume->id,
                        'score' => 72.00,
                        'current_status' => ApplicationStatus::SCREENING->value,
                        'applied_at' => Carbon::now()->subDay(),
                    ]
                );

                Interview::firstOrCreate(
                    ['application_id' => $application->id],
                    [
                        'scheduled_at' => Carbon::now()->addDays(3)->setHour(10)->setMinute(0),
                        'interviewer_id' => $recruiter1->id,
                        'duration' => 30,
                        'meeting_link' => 'https://meet.google.com/talentflow-screening',
                        'status' => InterviewStatus::SCHEDULED->value,
                        'notes' => 'Initial screening for the full-stack engineering role.',
                    ]
                );

                TechnicalTask::firstOrCreate(
                    ['application_id' => $application->id, 'title' => 'Create a Candidate Profile API'],
                    [
                        'recruiter_id' => $recruiter1->id,
                        'description' => 'Build a small REST endpoint that validates and returns a candidate profile.',
                        'instructions' => 'Include clear validation responses and one automated test.',
                        'assigned_at' => Carbon::now()->subHours(2),
                        'due_at' => Carbon::now()->addDays(4),
                        'status' => TechnicalTaskStatus::PENDING->value,
                    ]
                );
            });
        }
    }
}
