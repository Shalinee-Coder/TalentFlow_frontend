<?php

namespace Database\Factories;

use App\Models\Candidate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidateFactory extends Factory
{
    protected $model = Candidate::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->candidate(),
            'phone' => fake()->phoneNumber(),
            'total_experience' => fake()->randomFloat(1, 1, 12),
            'highest_education' => fake()->randomElement(['Bachelor', 'Master', 'PhD', 'Diploma']),
            'current_company' => fake()->company(),
            'current_position' => fake()->jobTitle(),
            'location' => fake()->city() . ', ' . fake()->country(),
        ];
    }
}
