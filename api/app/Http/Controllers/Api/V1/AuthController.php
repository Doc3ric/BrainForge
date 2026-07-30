<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {}

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->authService->registerUser($request->toDTO());
        return (new UserResource($user))->response()->setStatusCode(201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $this->authService->attemptLogin($request->toDTO());
        
        // Regenerate session for SPA
        $request->session()->regenerate();
        
        return (new UserResource($request->user()))->response()->setStatusCode(200);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return response()->json([
            'message' => 'Logged out successfully',
            'status' => 200,
        ], 200);
    }

    public function me(Request $request): UserResource
    {
        return new UserResource($request->user());
    }
}
