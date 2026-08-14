<?php

namespace App\Policies;

use App\Enums\Role;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function create(User $user): bool
    {
        return $user->roles->contains('id', Role::Administrator->value);
    }

    public function update(User $user, Task $task): bool
    {
        return $user->roles->contains('id', Role::Administrator->value)
            || $user->roles->contains('id', Role::Manager->value)
            || $task->user_id === $user->id;
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->roles->contains('id', Role::Administrator->value);
    }
}
