<?php

namespace Database\Seeders;

use App\Enums\Permission;
use App\Enums\Role as RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission as PermissionModel;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Permission::cases() as $permission) {
            PermissionModel::create(['name' => $permission->value]);
        }

        foreach (RoleEnum::cases() as $role) {
            $role = Role::create(['name' => $role->value]);

            $this->syncPermissionsToRole($role);
        }
    }

    private function syncPermissionsToRole(Role $role): void
    {
        $permissions = match ($role->name) {
            RoleEnum::MasterAdmin->value => [
                Permission::LIST_TEAM,
                Permission::CREATE_TEAM,
            ],
            RoleEnum::ClinicOwner->value => [
                Permission::SWITCH_TEAM,
                Permission::LIST_USER,
                Permission::CREATE_USER,
            ],
            RoleEnum::ClinicAdmin->value => [
                Permission::LIST_USER,
                Permission::CREATE_USER,
                Permission::LIST_TASK,
                Permission::CREATE_TASK,
                Permission::EDIT_TASK,
                Permission::DELETE_TASK,
            ],
            RoleEnum::Staff->value => [
                Permission::LIST_TASK,
                Permission::CREATE_TASK,
                Permission::EDIT_TASK,
                Permission::DELETE_TASK,
            ],
            RoleEnum::Doctor->value => [
                Permission::LIST_TASK,
                Permission::CREATE_TASK,
                Permission::EDIT_TASK,
            ],
            RoleEnum::Patient->value => [
                Permission::LIST_TASK,
            ],
            default => [],
        };

        $role->syncPermissions($permissions);
    }
}
