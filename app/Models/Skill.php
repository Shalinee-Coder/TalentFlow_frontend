<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    protected static function booted(): void
    {
        static::creating(function ($skill) {
            if (empty($skill->slug)) {
                $skill->slug = Str::slug($skill->name);
            }
        });
    }

    public function jobSkills(): HasMany
    {
        return $this->hasMany(JobSkill::class);
    }

    public function jobs(): BelongsToMany
    {
        return $this->belongsToMany(Job::class, 'job_skills')
            ->withPivot(['is_required', 'weight'])
            ->withTimestamps();
    }
}
