<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobSkill extends Model
{
    use HasFactory;

    protected $table = 'job_skills';

    protected $fillable = [
        'job_id',
        'skill_id',
        'is_required',
        'weight',
    ];

    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
            'weight' => 'integer',
        ];
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function skill(): BelongsTo
    {
        return $this->belongsTo(Skill::class);
    }
}
