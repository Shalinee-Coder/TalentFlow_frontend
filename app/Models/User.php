<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function candidate(): HasOne
    {
        return $this->hasOne(Candidate::class);
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class, 'recruiter_id');
    }

    public function interviews(): HasMany
    {
        return $this->hasMany(Interview::class, 'interviewer_id');
    }

    public function assignedTasks(): HasMany
    {
        return $this->hasMany(TechnicalTask::class, 'recruiter_id');
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(ApplicationStatusHistory::class, 'changed_by');
    }

    public function isAdmin(): bool
    {
        return $this->role?->name === UserRole::ADMIN->value;
    }

    public function isRecruiter(): bool
    {
        return $this->role?->name === UserRole::RECRUITER->value;
    }

    public function isCandidate(): bool
    {
        return $this->role?->name === UserRole::CANDIDATE->value;
    }

    public function hasRole(string|UserRole $role): bool
    {
        $roleName = $role instanceof UserRole ? $role->value : $role;
        return $this->role?->name === $roleName;
    }
}
