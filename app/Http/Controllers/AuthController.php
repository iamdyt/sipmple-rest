<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {
        $this->middleware(['throttle:10,1'])->except(['logOutUser']);
    }
    public function addUser(RegisterRequest $registerRequest): JsonResponse
    {
        $resource = $this->authService->addUser($registerRequest);
        return $this->success(data: $resource);
    }

    public function loginUser(LoginRequest $loginRequest): JsonResponse
    {
        $resource = $this->authService->loginUser($loginRequest);
        return $this->success(data: $resource);
    }

    public function logOutUser(): void
    {
        $this->authService->logOutUser();
    }
}
