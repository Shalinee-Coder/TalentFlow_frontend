<?php

namespace App\Models;

use App\Enums\JobStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'recruiter_id',
        'title',
        'department',
        'description',
        'required_experience',
        'salary_min',
        'salary_max',
        'application_deadline',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'required_experience' => 'decimal:1',
            'salary_min' => 'decimal:2',
            'salary_max' => 'decimal:2',
            'application_deadline' => 'datetime',
            'status' => JobStatus::class,
        ];
    }

    public function recruiter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }

    public function jobSkills(): HasMany
    {
        return $this->hasMany(JobSkill::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'job_skills')
            ->withPivot(['id', 'is_required', 'weight'])
            ->withTimestamps();
    }

    public function requiredSkills(): BelongsToMany
    {
        return $this->skills()->wherePivot('is_required', true);
    }

    public function optionalSkills(): BelongsToMany
    {
        return $this->skills()->wherePivot('is_required', false);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', JobStatus::PUBLISHED->value);
    }

    public function scopeOpenForApplications(Builder $query): Builder
    {
        return $query->where('status', JobStatus::PUBLISHED->value)
            ->where('application_deadline', '>=', now());
    }

    public function isAcceptingApplications(): bool
    {
        return $this->status === JobStatus::PUBLISHED && $this->application_deadline->isFuture();
    }
}
