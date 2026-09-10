<?php

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'role_id' => Role::firstOrCreate(
                ['name' => UserRole::CANDIDATE->value],
                ['display_name' => 'Candidate']
            )->id,
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'role_id' => Role::firstOrCreate(
                ['name' => UserRole::ADMIN->value],
                ['display_name' => 'Administrator']
            )->id,
        ]);
    }

    public function recruiter(): static
    {
        return $this->state(fn(array $attributes) => [
            'role_id' => Role::firstOrCreate(
                ['name' => UserRole::RECRUITER->value],
                ['display_name' => 'Recruiter']
            )->id,
        ]);
    }

    public function candidate(): static
    {
        return $this->state(fn(array $attributes) => [
            'role_id' => Role::firstOrCreate(
                ['name' => UserRole::CANDIDATE->value],
                ['display_name' => 'Candidate']
            )->id,
        ]);
    }
}
