<?php

namespace App\Services\Users;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class UserService
{
    public function paginateUsers(User $user, ?string $search = null): LengthAwarePaginator
    {
        return User::query()
            ->select(['id', 'name', 'email'])
            ->whereNot('id', $user->id)
            ->search($search)
            ->latest('id')
            ->paginate();
    }
}
