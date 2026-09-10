<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'candidate_id',
        'resume_id',
        'score',
        'current_status',
        'applied_at',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
            'current_status' => ApplicationStatus::class,
            'applied_at' => 'datetime',
        ];
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function resume(): BelongsTo
    {
        return $this->belongsTo(Resume::class);
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(ApplicationStatusHistory::class)->orderBy('id', 'desc');
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class)->orderBy('scheduled_at', 'desc');
    }

    public function latestInterview(): HasOne
    {
        return $this->hasOne(Interview::class)->latestOfMany('scheduled_at');
    }

    public function technicalTasks(): HasMany
    {
        return $this->hasMany(TechnicalTask::class)->orderBy('id', 'desc');
    }

    public function latestTechnicalTask(): HasOne
    {
        return $this->hasOne(TechnicalTask::class)->latestOfMany();
    }
}
