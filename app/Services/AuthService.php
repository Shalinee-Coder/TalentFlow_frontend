<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Models\Candidate;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Register a new Candidate user and profile.
     */
    public function registerCandidate(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $role = Role::firstOrCreate(
                ['name' => UserRole::CANDIDATE->value],
                ['display_name' => 'Candidate']
            );

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role_id' => $role->id,
            ]);

            $candidate = Candidate::create([
                'user_id' => $user->id,
                'phone' => $data['phone'] ?? null,
                'total_experience' => $data['total_experience'] ?? 0.0,
                'highest_education' => $data['highest_education'] ?? null,
                'current_company' => $data['current_company'] ?? null,
                'current_position' => $data['current_position'] ?? null,
                'location' => $data['location'] ?? null,
            ]);

            $user->load(['role', 'candidate']);

            $token = $user->createToken('auth_token')->plainTextToken;

            return [
                'user' => $user,
                'token' => $token,
                'role' => $role->name,
            ];
        });
    }

    /**
     * Login user and issue Sanctum token.
     */
    public function login(array $credentials): array
    {
        $user = User::with(['role', 'candidate'])->where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials do not match our records.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
            'role' => $user->role?->name,
        ];
    }

    /**
     * Revoke current token.
     */
    public function logout(User $user): void
    {
        $user->currentAccessToken()?->delete();
    }
}
