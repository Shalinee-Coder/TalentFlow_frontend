<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * Register a new candidate.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->authService->registerCandidate($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Candidate registration successful.',
            'data' => [
                'user' => new UserResource($result['user']),
                'token' => $result['token'],
                'role' => $result['role'],
            ],
        ], 201);
    }

    /**
     * Authenticate user and issue Sanctum token.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'data' => [
                'user' => new UserResource($result['user']),
                'token' => $result['token'],
                'role' => $result['role'],
            ],
        ]);
    }

    /**
     * Revoke current access token.
     */
    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.',
        ]);
    }

    /**
     * Return authenticated user profile.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load(['role', 'candidate']);

        return response()->json([
            'success' => true,
            'data' => new UserResource($user),
        ]);
    }
}
