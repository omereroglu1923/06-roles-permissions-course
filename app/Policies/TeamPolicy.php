<?php

namespace App\Policies;

use App\Enums\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permission::LIST_TEAM);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CREATE_TEAM);
    }
}
