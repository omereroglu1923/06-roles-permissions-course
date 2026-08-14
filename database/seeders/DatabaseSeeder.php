<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ])->assignRole(Role::Administrator);

        User::factory()->create([
            'name' => 'Simple User',
            'email' => 'user@example.com',
        ])->assignRole(Role::User);

        $this->call(TaskSeeder::class);
    }
}
