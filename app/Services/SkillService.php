<?php

namespace App\Services;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class SkillService
{
    public function listSkills(?string $search = null): Collection
    {
        $query = Skill::query();
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }
        return $query->orderBy('name')->get();
    }

    public function createSkill(array $data): Skill
    {
        return Skill::create([
            'name' => $data['name'],
            'slug' => !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['name']),
        ]);
    }

    public function updateSkill(Skill $skill, array $data): Skill
    {
        $skill->update([
            'name' => $data['name'] ?? $skill->name,
            'slug' => !empty($data['slug']) ? Str::slug($data['slug']) : Str::slug($data['name'] ?? $skill->name),
        ]);
        return $skill;
    }

    public function deleteSkill(Skill $skill): void
    {
        $skill->delete();
    }
}
