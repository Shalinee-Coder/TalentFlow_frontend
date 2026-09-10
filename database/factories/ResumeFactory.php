<?php

namespace Database\Factories;

use App\Enums\ResumeStatus;
use App\Models\Candidate;
use App\Models\Resume;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ResumeFactory extends Factory
{
    protected $model = Resume::class;

    public function definition(): array
    {
        return [
            'candidate_id' => Candidate::factory(),
            'file_path' => (string) Str::uuid() . '.pdf',
            'original_filename' => 'candidate_resume.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => fake()->numberBetween(100000, 2000000),
            'processing_status' => ResumeStatus::PROCESSED->value,
            'extracted_text' => fake()->paragraphs(4, true),
            'extracted_data' => [
                'email' => fake()->safeEmail(),
                'phone' => fake()->phoneNumber(),
                'links' => ['https://github.com/developer'],
                'skills' => [
                    ['id' => null, 'name' => 'PHP', 'slug' => 'php'],
                    ['id' => null, 'name' => 'Laravel', 'slug' => 'laravel'],
                    ['id' => null, 'name' => 'MySQL', 'slug' => 'mysql'],
                ],
                'experience_years' => 4.5,
                'education_level' => 'Bachelor',
            ],
            'processed_at' => now(),
        ];
    }
}
