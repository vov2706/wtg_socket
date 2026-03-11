<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\Users\UserResource;
use App\Services\Auth\AuthService;
use Illuminate\Auth\AuthenticationException;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService
    ) {
    }

    public function login(LoginRequest $request)
    {
        [$user, $token] = $this->authService->login($request->validated());

        return UserResource::make($user)->additional([
            'token' => $token,
        ]);
    }

    public function register(RegisterRequest $request)
    {
        [$user, $token] = $this->authService->register($request->validated());

        return UserResource::make($user)->additional([
            'token' => $token,
        ]);
    }

    /**
     * @throws AuthenticationException
     */
    public function logout()
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            throw new AuthenticationException();
        }

        $user->currentAccessToken()->delete();

        return $this->responseSuccess('Logged out');
    }
}
