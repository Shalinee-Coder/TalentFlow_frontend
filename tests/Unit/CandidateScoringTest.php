<?php

namespace Tests\Unit;

use App\Models\Job;
use App\Models\Skill;
use App\Services\CandidateScoringService;
use Tests\TestCase;

class CandidateScoringTest extends TestCase
{
    protected CandidateScoringService $scoringService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->scoringService = new CandidateScoringService();
    }

    public function test_perfect_candidate_receives_100_points(): void
    {
        $job = Job::factory()->create(['required_experience' => 5.0]);

        $skill1 = Skill::factory()->create(['name' => 'PHP', 'slug' => 'php']);
        $skill2 = Skill::factory()->create(['name' => 'Laravel', 'slug' => 'laravel']);
        $skill3 = Skill::factory()->create(['name' => 'Docker', 'slug' => 'docker']);

        $job->skills()->attach([
            $skill1->id => ['is_required' => true, 'weight' => 5],
            $skill2->id => ['is_required' => true, 'weight' => 5],
            $skill3->id => ['is_required' => false, 'weight' => 2],
        ]);

        $candidateData = [
            'skills' => [
                ['name' => 'PHP', 'slug' => 'php'],
                ['name' => 'Laravel', 'slug' => 'laravel'],
                ['name' => 'Docker', 'slug' => 'docker'],
            ],
            'experience_years' => 6.0, // Exceeds 5.0 -> 25 pts
            'education_level' => 'Doctorate', // 15 pts
            'links' => ['https://github.com/developer'], // 5 pts
            'email' => 'dev@example.com', // 5 pts
            'phone' => '+1-555-0100',
        ];

        $breakdown = $this->scoringService->calculateDetailedBreakdown($job, $candidateData);

        $this->assertEquals(40.0, $breakdown['required_skills_score']);
        $this->assertEquals(10.0, $breakdown['optional_skills_score']);
        $this->assertEquals(25.0, $breakdown['experience_score']);
        $this->assertEquals(15.0, $breakdown['education_score']);
        $this->assertEquals(10.0, $breakdown['bonus_score']);
        $this->assertEquals(100.0, $breakdown['total_score']);
    }

    public function test_proportional_experience_scoring(): void
    {
        $job = Job::factory()->create(['required_experience' => 4.0]);

        // Candidate has 2 years out of 4 -> exactly 50% of 25 = 12.5 pts
        $candidateData = [
            'skills' => [],
            'experience_years' => 2.0,
            'education_level' => 'High School',
            'links' => [],
            'email' => '',
            'phone' => '',
        ];

        $breakdown = $this->scoringService->calculateDetailedBreakdown($job, $candidateData);

        $this->assertEquals(12.5, $breakdown['experience_score']);
    }

    public function test_education_scoring_hierarchy(): void
    {
        $job = Job::factory()->create(['required_experience' => 0.0]);

        $levels = [
            'PhD in AI' => 15.0,
            'Master of Computer Science' => 13.0,
            'Bachelor of Technology' => 10.0,
            'Associate Diploma' => 7.0,
            'High School' => 4.0,
        ];

        foreach ($levels as $educationString => $expectedPoints) {
            $breakdown = $this->scoringService->calculateDetailedBreakdown($job, [
                'skills' => [],
                'experience_years' => 0,
                'education_level' => $educationString,
            ]);

            $this->assertEquals($expectedPoints, $breakdown['education_score'], "Failed scoring for education level: {$educationString}");
        }
    }
}
