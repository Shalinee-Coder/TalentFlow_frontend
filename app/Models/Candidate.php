<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'total_experience',
        'highest_education',
        'current_company',
        'current_position',
        'location',
    ];

    protected function casts(): array
    {
        return [
            'total_experience' => 'decimal:1',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resumes(): HasMany
    {
        return $this->hasMany(Resume::class);
    }

    public function latestResume(): HasOne
    {
        return $this->hasOne(Resume::class)->latestOfMany();
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(TaskSubmission::class);
    }
}
