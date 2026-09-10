<?php

namespace App\Models;

use App\Enums\TechnicalTaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TechnicalTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'recruiter_id',
        'title',
        'description',
        'instructions',
        'assigned_at',
        'due_at',
        'status',
        'reminder_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'assigned_at' => 'datetime',
            'due_at' => 'datetime',
            'reminder_sent_at' => 'datetime',
            'status' => TechnicalTaskStatus::class,
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function recruiter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(TaskSubmission::class)->orderBy('id', 'desc');
    }

    public function latestSubmission(): HasOne
    {
        return $this->hasOne(TaskSubmission::class)->latestOfMany();
    }

    public function isOverdue(): bool
    {
        return $this->due_at->isPast() && in_array($this->status, [
            TechnicalTaskStatus::PENDING,
            TechnicalTaskStatus::IN_PROGRESS,
        ], true);
    }
}
