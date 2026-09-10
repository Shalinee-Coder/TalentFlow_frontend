<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $skills = [
            'PHP',
            'Laravel',
            'MySQL',
            'JavaScript',
            'React',
            'Vue',
            'REST API',
            'Git',
            'Docker',
            'AWS',
            'Python',
            'Redis',
            'GraphQL',
            'TypeScript',
            'TailwindCSS',
            'PostgreSQL',
            'Microservices',
            'CI/CD',
        ];

        foreach ($skills as $skillName) {
            Skill::firstOrCreate(
                ['slug' => Str::slug($skillName)],
                ['name' => $skillName]
            );
        }
    }
}
