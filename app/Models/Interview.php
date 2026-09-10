<?php

namespace App\Models;

use App\Enums\InterviewStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Interview extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'interviewer_id',
        'scheduled_at',
        'duration',
        'meeting_link',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'duration' => 'integer',
            'status' => InterviewStatus::class,
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function interviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'interviewer_id');
    }

    public function getEndAtAttribute(): Carbon
    {
        return $this->scheduled_at->copy()->addMinutes($this->duration);
    }
}
