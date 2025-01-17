<?php

namespace App\Services;

use App\Exceptions\CustomException;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Hash;


class AuthService
{

    public function __construct() {}

    public function addUser(RegisterRequest $registeredRequest): JsonResource | array
    {
        $data = $registeredRequest->validated();
        /** @var \App\Models\User $user */
        $user = User::create($data);
        return new AuthResource($user);
    }

    public function loginUser(LoginRequest $loginRequest): JsonResource | array
    {
        $data = $loginRequest->validated();
        $user = User::query()->where('username',$data['username'])->first();
        if (!$user || !Hash::check($data['password'], $user?->password)) {
            throw new CustomException('Invalid credentials');
        }
        $token = $user->createToken('user')->plainTextToken;
        $user['token'] = $token;
        return new AuthResource($user);
    }

    public function logOutUser(): void
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->tokens()->delete();
    }
}
