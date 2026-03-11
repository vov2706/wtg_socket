<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\Users\UserResource;
use App\Services\Users\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function profile()
    {
        return UserResource::make(auth()->user()->load('notReadMessages.sender'));
    }

    public function index(Request $request, UserService $userService)
    {
        $validated = $request->validate([
            'search' => ['nullable', 'string',]
        ]);

        return UserResource::collection(
            $userService->paginateUsers(auth()->user(), data_get($validated, 'search'))
        );
    }
}
