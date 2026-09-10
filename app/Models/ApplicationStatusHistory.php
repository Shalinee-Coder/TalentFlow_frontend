<?php

namespace App\Models;

use App\Enums\ApplicationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'from_status',
        'to_status',
        'changed_by',
        'changed_at',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'from_status' => ApplicationStatus::class,
            'to_status' => ApplicationStatus::class,
            'changed_at' => 'datetime',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function changedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
