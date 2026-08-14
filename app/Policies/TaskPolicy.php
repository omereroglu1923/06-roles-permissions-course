<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function create(User $user): bool
    {
        return $user->role_id === Role::Administrator;
    }

    public function update(User $user, Task $task): bool
    {
        return $user->role_id === Role::Administrator
            || $user->role_id === Role::Manager
            || $task->user_id === $user->id;
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->role_id === Role::Administrator;
    }
}
