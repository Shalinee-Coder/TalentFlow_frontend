<?php

namespace App\Models;

use App\Enums\TaskSubmissionStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'technical_task_id',
        'candidate_id',
        'submission_content',
        'submission_url',
        'submitted_at',
        'review_notes',
        'reviewed_by',
        'reviewed_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'status' => TaskSubmissionStatus::class,
        ];
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(TechnicalTask::class, 'technical_task_id');
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
