<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Candidate;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', UserRole::ADMIN->value)->firstOrFail();
        $recruiterRole = Role::where('name', UserRole::RECRUITER->value)->firstOrFail();
        $candidateRole = Role::where('name', UserRole::CANDIDATE->value)->firstOrFail();

        // 1. Admin User
        User::firstOrCreate(
            ['email' => 'admin@talentflow.local'],
            [
                'role_id' => $adminRole->id,
                'name' => 'Rajesh Sharma (System Administrator)',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // 2. Recruiters
        $recruiter1 = User::firstOrCreate(
            ['email' => 'recruiter1@talentflow.local'],
            [
                'role_id' => $recruiterRole->id,
                'name' => 'Priya Nair (Lead Tech Recruiter)',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $recruiter2 = User::firstOrCreate(
            ['email' => 'recruiter2@talentflow.local'],
            [
                'role_id' => $recruiterRole->id,
                'name' => 'Vikram Malhotra (Engineering Hiring Manager)',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // 3. Indian Candidates
        $candidates = [
            [
                'name' => 'Aarav Sharma',
                'email' => 'candidate1@talentflow.local',
                'phone' => '+91-98201-12345',
                'experience' => 5.5,
                'education' => 'Master',
                'company' => 'Razorpay',
                'position' => 'Senior Laravel Architect',
                'location' => 'Bengaluru, Karnataka',
            ],
            [
                'name' => 'Ananya Iyer',
                'email' => 'candidate2@talentflow.local',
                'phone' => '+91-98450-23456',
                'experience' => 3.2,
                'education' => 'Bachelor',
                'company' => 'Swiggy',
                'position' => 'Full Stack Developer',
                'location' => 'Pune, Maharashtra',
            ],
            [
                'name' => 'Rohan Verma',
                'email' => 'candidate3@talentflow.local',
                'phone' => '+91-98110-34567',
                'experience' => 7.0,
                'education' => 'Bachelor',
                'company' => 'Flipkart',
                'position' => 'Lead Backend Architect',
                'location' => 'Hyderabad, Telangana',
            ],
            [
                'name' => 'Sneha Kulkarni',
                'email' => 'candidate4@talentflow.local',
                'phone' => '+91-98220-45678',
                'experience' => 2.5,
                'education' => 'Bachelor',
                'company' => 'Jio Platforms',
                'position' => 'Junior Web Developer',
                'location' => 'Mumbai, Maharashtra',
            ],
            [
                'name' => 'Aditya Rao',
                'email' => 'candidate5@talentflow.local',
                'phone' => '+91-98990-56789',
                'experience' => 8.5,
                'education' => 'Doctorate',
                'company' => 'Zomato',
                'position' => 'Principal Software Engineer',
                'location' => 'Gurugram, Haryana',
            ],
            [
                'name' => 'Pooja Deshmukh',
                'email' => 'candidate6@talentflow.local',
                'phone' => '+91-98700-67890',
                'experience' => 4.0,
                'education' => 'Bachelor',
                'company' => 'Paytm',
                'position' => 'Backend Engineer',
                'location' => 'Noida, Uttar Pradesh',
            ],
            [
                'name' => 'Arjun Reddy',
                'email' => 'candidate7@talentflow.local',
                'phone' => '+91-98490-78901',
                'experience' => 3.8,
                'education' => 'Bachelor',
                'company' => 'CRED',
                'position' => 'Full Stack Developer',
                'location' => 'Bengaluru, Karnataka',
            ],
            [
                'name' => 'Neha Gupta',
                'email' => 'candidate8@talentflow.local',
                'phone' => '+91-98100-89012',
                'experience' => 6.0,
                'education' => 'Master',
                'company' => 'PhonePe',
                'position' => 'Senior Cloud Infrastructure Engineer',
                'location' => 'Bengaluru, Karnataka',
            ],
        ];

        foreach ($candidates as $cand) {
            $user = User::firstOrCreate(
                ['email' => $cand['email']],
                [
                    'role_id' => $candidateRole->id,
                    'name' => $cand['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            Candidate::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'phone' => $cand['phone'],
                    'total_experience' => $cand['experience'],
                    'highest_education' => $cand['education'],
                    'current_company' => $cand['company'],
                    'current_position' => $cand['position'],
                    'location' => $cand['location'],
                ]
            );
        }
    }
}
