<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use LogicException;

final readonly class AuthService
{
    public function register(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $exists = User::query()
                ->where('email', $data['email'])
                ->exists();

            if ($exists) {
                throw ValidationException::withMessages([
                    'email' => ['A user with this email already exists.'],
                ]);
            }

            $user = User::query()->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $token = $user->createToken('web')->plainTextToken;

            return [$user, $token];
        });
    }

    public function login(array $credentials): array
    {
        $userProvider = app(AuthManager::class)->createUserProvider('users');

        if (! $userProvider) {
            throw new LogicException('User provider not found.');
        }

        $user = $userProvider->retrieveByCredentials($credentials);

        if ($userProvider->validateCredentials($user, $credentials)) {
            $token = $user->createToken('web')->plainTextToken;

            return [$user, $token];
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }
}
