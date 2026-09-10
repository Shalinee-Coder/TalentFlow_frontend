<?php

namespace App\Services;

use App\Models\Job;

class CandidateScoringService
{
    /**
     * Calculate transparent matching score (0.00 to 100.00) between a Job and extracted candidate data.
     *
     * Formula:
     * - Required Skills: 40 points max
     * - Optional Skills: 10 points max
     * - Experience Match: 25 points max
     * - Education Level: 15 points max
     * - Bonus / Profile completeness: 10 points max
     * Total = 100.00
     */
    public function calculateScore(Job $job, array $candidateData): float
    {
        $breakdown = $this->calculateDetailedBreakdown($job, $candidateData);
        return $breakdown['total_score'];
    }

    /**
     * Compute a full transparent breakdown for scoring insights.
     */
    public function calculateDetailedBreakdown(Job $job, array $candidateData): array
    {
        // 1. Required Skills Score (Max 40)
        $requiredSkillsScore = $this->scoreRequiredSkills($job, $candidateData['skills'] ?? []);

        // 2. Optional Skills Score (Max 10)
        $optionalSkillsScore = $this->scoreOptionalSkills($job, $candidateData['skills'] ?? []);

        // 3. Experience Score (Max 25)
        $experienceScore = $this->scoreExperience($job, (float) ($candidateData['experience_years'] ?? 0));

        // 4. Education Score (Max 15)
        $educationScore = $this->scoreEducation($candidateData['education_level'] ?? '');

        // 5. Bonus Score (Max 10)
        $bonusScore = $this->scoreBonus($candidateData);

        $total = round(min(100.0, $requiredSkillsScore + $optionalSkillsScore + $experienceScore + $educationScore + $bonusScore), 2);

        return [
            'required_skills_score' => round($requiredSkillsScore, 2),
            'optional_skills_score' => round($optionalSkillsScore, 2),
            'experience_score' => round($experienceScore, 2),
            'education_score' => round($educationScore, 2),
            'bonus_score' => round($bonusScore, 2),
            'total_score' => $total,
        ];
    }

    /**
     * Score required skills based on relative weights (Max 40 pts).
     */
    protected function scoreRequiredSkills(Job $job, array $candidateSkills): float
    {
        $job->loadMissing('jobSkills.skill');
        $required = $job->jobSkills->where('is_required', true);

        if ($required->isEmpty()) {
            return 40.0; // If no required skills specified, full points
        }

        $totalWeight = $required->sum('weight');
        if ($totalWeight <= 0) {
            $totalWeight = $required->count();
        }

        $candidateSkillSlugs = array_map(function ($s) {
            return strtolower($s['slug'] ?? $s['name'] ?? '');
        }, $candidateSkills);

        $matchedWeight = 0;
        foreach ($required as $jobSkill) {
            $skillSlug = strtolower($jobSkill->skill?->slug ?? '');
            $skillName = strtolower($jobSkill->skill?->name ?? '');

            if (in_array($skillSlug, $candidateSkillSlugs, true) || in_array($skillName, $candidateSkillSlugs, true)) {
                $matchedWeight += ($jobSkill->weight > 0 ? $jobSkill->weight : 1);
            }
        }

        return ($matchedWeight / $totalWeight) * 40.0;
    }

    /**
     * Score optional skills (Max 10 pts).
     */
    protected function scoreOptionalSkills(Job $job, array $candidateSkills): float
    {
        $job->loadMissing('jobSkills.skill');
        $optional = $job->jobSkills->where('is_required', false);

        if ($optional->isEmpty()) {
            // Give 10 pts if candidate has at least 3 skills detected
            return count($candidateSkills) >= 3 ? 10.0 : (count($candidateSkills) * 3.33);
        }

        $totalWeight = $optional->sum('weight');
        if ($totalWeight <= 0) {
            $totalWeight = $optional->count();
        }

        $candidateSkillSlugs = array_map(function ($s) {
            return strtolower($s['slug'] ?? $s['name'] ?? '');
        }, $candidateSkills);

        $matchedWeight = 0;
        foreach ($optional as $jobSkill) {
            $skillSlug = strtolower($jobSkill->skill?->slug ?? '');
            $skillName = strtolower($jobSkill->skill?->name ?? '');

            if (in_array($skillSlug, $candidateSkillSlugs, true) || in_array($skillName, $candidateSkillSlugs, true)) {
                $matchedWeight += ($jobSkill->weight > 0 ? $jobSkill->weight : 1);
            }
        }

        return ($matchedWeight / $totalWeight) * 10.0;
    }

    /**
     * Score experience based on job's required experience (Max 25 pts).
     */
    protected function scoreExperience(Job $job, float $candidateExperience): float
    {
        $requiredExp = (float) $job->required_experience;

        if ($requiredExp <= 0.0) {
            return 25.0; // Entry level job: full score
        }

        if ($candidateExperience >= $requiredExp) {
            return 25.0; // Meets or exceeds
        }

        // Proportional score
        return max(0.0, ($candidateExperience / $requiredExp) * 25.0);
    }

    /**
     * Score education level (Max 15 pts).
     */
    protected function scoreEducation(string $education): float
    {
        $level = strtolower($education);

        if (str_contains($level, 'doctorate') || str_contains($level, 'phd')) {
            return 15.0;
        }

        if (str_contains($level, 'master') || str_contains($level, 'mba') || str_contains($level, 'msc')) {
            return 13.0;
        }

        if (str_contains($level, 'bachelor') || str_contains($level, 'bsc') || str_contains($level, 'btech') || str_contains($level, 'be')) {
            return 10.0;
        }

        if (str_contains($level, 'diploma') || str_contains($level, 'associate')) {
            return 7.0;
        }

        return 4.0;
    }

    /**
     * Score bonus alignment: GitHub/portfolio link, contact completeness (Max 10 pts).
     */
    protected function scoreBonus(array $candidateData): float
    {
        $bonus = 0.0;

        // Has GitHub or portfolio link: +5 pts
        if (!empty($candidateData['links'])) {
            $bonus += 5.0;
        }

        // Has phone number and email verified: +5 pts
        if (!empty($candidateData['email']) && !empty($candidateData['phone'])) {
            $bonus += 5.0;
        }

        return min(10.0, $bonus);
    }
}
