<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
    public function test_candidate_can_register(): void
    {
        $payload = [
            'name' => 'Jane Candidate',
            'email' => 'jane@example.com',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
            'phone' => '+1-555-1234',
            'total_experience' => 4.5,
            'highest_education' => 'Bachelor',
            'current_company' => 'Tech Corp',
            'current_position' => 'Developer',
            'location' => 'Chicago, IL',
        ];

        $response = $this->postJson('/api/v1/auth/register', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.role', 'candidate')
            ->assertJsonPath('data.user.email', 'jane@example.com');

        $this->assertDatabaseHas('users', ['email' => 'jane@example.com']);
        $this->assertDatabaseHas('candidates', ['phone' => '+1-555-1234']);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->candidate()->create([
            'email' => 'testuser@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'testuser@example.com',
            'password' => 'password123',
        ]);

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => ['user', 'token', 'role'],
            ]);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        $user = User::factory()->candidate()->create([
            'email' => 'testuser@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'testuser@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_user_can_retrieve_profile_via_me(): void
    {
        $user = User::factory()->candidate()->create();

        $response = $this->actingAs($user, 'sanctum')->getJson('/api/v1/auth/me');

        $response->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $user->id)
            ->assertJsonPath('data.email', $user->email);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->candidate()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/v1/auth/logout');

        $response->assertOk()
            ->assertJsonPath('success', true);
    }

    public function test_candidate_cannot_access_recruiter_routes(): void
    {
        $candidateUser = User::factory()->candidate()->create();

        $response = $this->actingAs($candidateUser, 'sanctum')->postJson('/api/v1/jobs', [
            'title' => 'Sneaky Job',
            'department' => 'IT',
            'description' => 'Test',
            'required_experience' => 2,
            'application_deadline' => now()->addDays(5)->toIso8601String(),
        ]);

        $response->assertStatus(403);
    }
}
