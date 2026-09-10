<?php

namespace App\Models;

use App\Enums\ResumeStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resume extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'file_path',
        'original_filename',
        'mime_type',
        'file_size',
        'processing_status',
        'extracted_text',
        'extracted_data',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
            'processing_status' => ResumeStatus::class,
            'extracted_data' => 'array',
            'processed_at' => 'datetime',
        ];
    }

    public function candidate(): BelongsTo
    {
        return $this->belongsTo(Candidate::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function isProcessed(): bool
    {
        return $this->processing_status === ResumeStatus::PROCESSED;
    }
}
