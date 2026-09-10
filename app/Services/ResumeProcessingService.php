<?php

namespace App\Services;

use App\Enums\ResumeStatus;
use App\Models\Resume;
use App\Models\Skill;
use App\Services\Contracts\ResumeParserInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ResumeProcessingService
{
    public function __construct(
        protected ResumeParserInterface $parser,
        protected CandidateScoringService $scoringService
    ) {}

    /**
     * Process an uploaded resume: extract text, parse structured data, and update candidate/application score.
     */
    public function process(Resume $resume): Resume
    {
        $resume->update(['processing_status' => ResumeStatus::PROCESSING->value]);

        try {
            $absolutePath = Storage::disk('resumes')->path($resume->file_path);

            // 1. Extract raw text
            $rawText = $this->parser->extractText($absolutePath);

            // 2. Parse structured information
            $extractedData = $this->parseStructuredData($rawText);

            // 3. Update resume record
            $resume->update([
                'processing_status' => ResumeStatus::PROCESSED->value,
                'extracted_text' => $rawText,
                'extracted_data' => $extractedData,
                'processed_at' => Carbon::now(),
            ]);

            // 4. Update Candidate profile if profile fields are empty
            $this->syncCandidateProfile($resume, $extractedData);

            // 5. Re-evaluate any applications linked to this resume
            foreach ($resume->applications as $application) {
                $score = $this->scoringService->calculateScore($application->job, $extractedData);
                $application->update(['score' => $score]);
            }

            return $resume->fresh();

        } catch (Exception $e) {
            Log::error("Failed to process resume #{$resume->id}: " . $e->getMessage(), [
                'resume_id' => $resume->id,
                'trace' => $e->getTraceAsString(),
            ]);

            $resume->update([
                'processing_status' => ResumeStatus::FAILED->value,
            ]);

            throw $e;
        }
    }

    /**
     * Parse structured sections from extracted text.
     */
    public function parseStructuredData(string $text): array
    {
        return [
            'email' => $this->detectEmail($text),
            'phone' => $this->detectPhone($text),
            'links' => $this->detectLinks($text),
            'skills' => $this->detectSkills($text),
            'experience_years' => $this->detectExperienceYears($text),
            'education_level' => $this->detectEducationLevel($text),
            'raw_word_count' => str_word_count($text),
        ];
    }

    protected function detectEmail(string $text): ?string
    {
        if (preg_match('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $text, $matches)) {
            return strtolower($matches[0]);
        }
        return null;
    }

    protected function detectPhone(string $text): ?string
    {
        if (preg_match('/(?:\+?\d{1,3}[-.\s]?)?\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}/', $text, $matches)) {
            return trim($matches[0]);
        }
        return null;
    }

    protected function detectLinks(string $text): array
    {
        $links = [];
        if (preg_match_all('/https?:\/\/[^\s]+|github\.com\/[a-zA-Z0-9_-]+|linkedin\.com\/in\/[a-zA-Z0-9_-]+/i', $text, $matches)) {
            $links = array_values(array_unique($matches[0]));
        }
        return $links;
    }

    /**
     * Detect technical and soft skills by matching against the system skill registry.
     */
    protected function detectSkills(string $text): array
    {
        $detected = [];
        $dbSkills = Skill::all();

        $textLower = ' ' . strtolower($text) . ' ';

        foreach ($dbSkills as $skill) {
            $skillName = strtolower($skill->name);
            // Match with boundary checks e.g. "PHP", "Go", "C++", "Vue.js", "React"
            $escaped = preg_quote($skillName, '/');
            if (preg_match('/(?:\b|[^a-zA-Z0-9])' . $escaped . '(?:\b|[^a-zA-Z0-9])/i', $textLower)) {
                $detected[] = [
                    'id' => $skill->id,
                    'name' => $skill->name,
                    'slug' => $skill->slug,
                ];
            }
        }

        // Additional common industry skills fallback if database is not yet seeded
        if (empty($detected)) {
            $commonList = ['php', 'laravel', 'mysql', 'javascript', 'react', 'vue', 'docker', 'git', 'aws', 'rest api', 'python', 'java'];
            foreach ($commonList as $item) {
                if (str_contains($textLower, $item)) {
                    $detected[] = [
                        'id' => null,
                        'name' => ucfirst($item),
                        'slug' => Str::slug($item),
                    ];
                }
            }
        }

        return $detected;
    }

    /**
     * Detect total years of experience using regex patterns.
     */
    protected function detectExperienceYears(string $text): float
    {
        // Pattern: "5+ years", "3.5 years of experience", "4 years experience"
        if (preg_match('/(\d+(?:\.\d+)?)\s*\+?\s*years?(?:\s+of)?\s+experience/i', $text, $matches)) {
            return (float) $matches[1];
        }

        if (preg_match('/experience\s*:\s*(\d+(?:\.\d+)?)\s*\+?\s*years?/i', $text, $matches)) {
            return (float) $matches[1];
        }

        // Check for total year mentions
        if (preg_match_all('/(\d{1,2})\+?\s*years?/i', $text, $matches)) {
            $numbers = array_map('floatval', $matches[1]);
            // Filter reasonable career years (1 - 30)
            $valid = array_filter($numbers, fn($n) => $n >= 1 && $n <= 30);
            if (!empty($valid)) {
                return (float) max($valid);
            }
        }

        return 0.0;
    }

    /**
     * Detect highest education degree level.
     */
    protected function detectEducationLevel(string $text): string
    {
        $textLower = strtolower($text);

        if (preg_match('/\b(phd|doctorate|doctor of philosophy)\b/i', $textLower)) {
            return 'Doctorate';
        }

        if (preg_match('/\b(master|master\'s|msc|m\.s\.|mba|mtech|m\.tech)\b/i', $textLower)) {
            return 'Master';
        }

        if (preg_match('/\b(bachelor|bachelor\'s|bsc|b\.s\.|btech|b\.tech|be|b\.e\.)\b/i', $textLower)) {
            return 'Bachelor';
        }

        if (preg_match('/\b(associate|diploma)\b/i', $textLower)) {
            return 'Diploma';
        }

        return 'High School / Other';
    }

    /**
     * Synchronize candidate profile with detected resume details if not already present.
     */
    protected function syncCandidateProfile(Resume $resume, array $data): void
    {
        $candidate = $resume->candidate;
        if (!$candidate) {
            return;
        }

        $updates = [];
        if (empty($candidate->phone) && !empty($data['phone'])) {
            $updates['phone'] = $data['phone'];
        }
        if ($candidate->total_experience <= 0 && !empty($data['experience_years'])) {
            $updates['total_experience'] = $data['experience_years'];
        }
        if (empty($candidate->highest_education) && !empty($data['education_level'])) {
            $updates['highest_education'] = $data['education_level'];
        }

        if (!empty($updates)) {
            $candidate->update($updates);
        }
    }
}
