<?php

namespace Database\Factories;

use App\Enums\InterviewStatus;
use App\Models\Application;
use App\Models\Interview;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InterviewFactory extends Factory
{
    protected $model = Interview::class;

    public function definition(): array
    {
        return [
            'application_id' => Application::factory(),
            'interviewer_id' => User::factory()->recruiter(),
            'scheduled_at' => now()->addDays(2)->setHour(14)->setMinute(0),
            'duration' => 60,
            'meeting_link' => 'https://meet.google.com/' . fake()->slug(3),
            'status' => InterviewStatus::SCHEDULED->value,
            'notes' => fake()->sentence(),
        ];
    }
}
