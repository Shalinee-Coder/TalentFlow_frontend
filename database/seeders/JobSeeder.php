<?php

namespace Database\Seeders;

use App\Enums\JobStatus;
use App\Models\Job;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        $recruiter1 = User::where('email', 'recruiter1@talentflow.local')->first();
        $recruiter2 = User::where('email', 'recruiter2@talentflow.local')->first();

        if (!$recruiter1) {
            return;
        }

        $php = Skill::where('slug', 'php')->first();
        $laravel = Skill::where('slug', 'laravel')->first();
        $mysql = Skill::where('slug', 'mysql')->first();
        $docker = Skill::where('slug', 'docker')->first();
        $react = Skill::where('slug', 'react')->first();
        $vue = Skill::where('slug', 'vue')->first();
        $restApi = Skill::where('slug', 'rest-api')->first();
        $aws = Skill::where('slug', 'aws')->first();

        // Job 1: Senior Laravel Architect - Bengaluru
        $job1 = Job::firstOrCreate(
            ['title' => 'Senior Laravel Architect', 'recruiter_id' => $recruiter1->id],
            [
                'department' => 'Engineering (Bengaluru, Hybrid)',
                'description' => 'We are seeking a Senior Laravel Architect to design and scale our mission-critical fintech applications. You will spearhead architectural choices, optimize high-throughput MySQL schemas, and mentor senior developers in our Bengaluru innovation center.',
                'required_experience' => 5.0,
                'salary_min' => 2400000.00,
                'salary_max' => 3600000.00,
                'application_deadline' => now()->addDays(20),
                'status' => JobStatus::PUBLISHED->value,
            ]
        );

        if ($php && $laravel && $mysql && $docker && $aws) {
            $job1->skills()->syncWithoutDetaching([
                $php->id => ['is_required' => true, 'weight' => 5],
                $laravel->id => ['is_required' => true, 'weight' => 5],
                $mysql->id => ['is_required' => true, 'weight' => 3],
                $docker->id => ['is_required' => false, 'weight' => 2],
                $aws->id => ['is_required' => false, 'weight' => 2],
            ]);
        }

        // Job 2: Full Stack Engineer (Vue & Laravel) - Pune
        $recruiterForJob2 = $recruiter2 ?? $recruiter1;
        $job2 = Job::firstOrCreate(
            ['title' => 'Full Stack Engineer (Vue & Laravel)', 'recruiter_id' => $recruiterForJob2->id],
            [
                'department' => 'Product Engineering (Pune, Remote)',
                'description' => 'Join our fast-paced product squad to build intuitive web interfaces with Vue.js coupled with robust RESTful APIs in Laravel. Based in Pune or Remote across India.',
                'required_experience' => 3.0,
                'salary_min' => 1400000.00,
                'salary_max' => 2200000.00,
                'application_deadline' => now()->addDays(14),
                'status' => JobStatus::PUBLISHED->value,
            ]
        );

        if ($laravel && $vue && $restApi && $mysql) {
            $job2->skills()->syncWithoutDetaching([
                $laravel->id => ['is_required' => true, 'weight' => 4],
                $restApi->id => ['is_required' => true, 'weight' => 3],
                $vue->id => ['is_required' => false, 'weight' => 2],
                $mysql->id => ['is_required' => true, 'weight' => 2],
            ]);
        }

        // Job 3: Staff Cloud & DevOps Architect - Hyderabad
        $job3 = Job::firstOrCreate(
            ['title' => 'Staff Cloud & DevOps Architect', 'recruiter_id' => $recruiter1->id],
            [
                'department' => 'Platform Infrastructure (Hyderabad, Hybrid)',
                'description' => 'Architect modern multi-cloud pipelines using Kubernetes, Terraform, and AWS for high-scale enterprise workloads.',
                'required_experience' => 6.0,
                'salary_min' => 2800000.00,
                'salary_max' => 4200000.00,
                'application_deadline' => now()->addDays(30),
                'status' => JobStatus::PUBLISHED->value,
            ]
        );

        if ($aws && $docker && $restApi) {
            $job3->skills()->syncWithoutDetaching([
                $aws->id => ['is_required' => true, 'weight' => 5],
                $docker->id => ['is_required' => true, 'weight' => 4],
                $restApi->id => ['is_required' => false, 'weight' => 2],
            ]);
        }

        // Job 4: Backend API Engineer - Chennai
        $job4 = Job::firstOrCreate(
            ['title' => 'Backend API Engineer', 'recruiter_id' => $recruiter1->id],
            [
                'department' => 'Engineering (Chennai, Hybrid)',
                'description' => 'Build reliable Laravel APIs and services for our growing product platform.',
                'required_experience' => 2.0,
                'salary_min' => 1000000.00,
                'salary_max' => 1800000.00,
                'application_deadline' => now()->addDays(18),
                'status' => JobStatus::PUBLISHED->value,
            ]
        );

        if ($php && $laravel && $mysql && $restApi) {
            $job4->skills()->syncWithoutDetaching([
                $php->id => ['is_required' => true, 'weight' => 4],
                $laravel->id => ['is_required' => true, 'weight' => 5],
                $mysql->id => ['is_required' => true, 'weight' => 3],
                $restApi->id => ['is_required' => false, 'weight' => 2],
            ]);
        }

        // Job 5: Frontend React Developer - Remote
        $job5 = Job::firstOrCreate(
            ['title' => 'Frontend React Developer', 'recruiter_id' => $recruiter1->id],
            [
                'department' => 'Product Engineering (Remote)',
                'description' => 'Create accessible React interfaces and reusable components for our web products.',
                'required_experience' => 2.0,
                'salary_min' => 900000.00,
                'salary_max' => 1600000.00,
                'application_deadline' => now()->addDays(24),
                'status' => JobStatus::PUBLISHED->value,
            ]
        );

        if ($react && $vue && $restApi) {
            $job5->skills()->syncWithoutDetaching([
                $react->id => ['is_required' => true, 'weight' => 5],
                $vue->id => ['is_required' => false, 'weight' => 3],
                $restApi->id => ['is_required' => true, 'weight' => 2],
            ]);
        }
    }
}
