<?php

namespace App\Http\Controllers\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\Users\UserResource;

class UserController extends Controller
{
    public function profile()
    {
        return UserResource::make(auth()->user());
    }
}
