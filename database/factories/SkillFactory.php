<?php

namespace Database\Factories;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SkillFactory extends Factory
{
    protected $model = Skill::class;

    public function definition(): array
    {
        $name = fake()->unique()->word() . ' ' . fake()->randomNumber(3);
        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
        ];
    }
}
