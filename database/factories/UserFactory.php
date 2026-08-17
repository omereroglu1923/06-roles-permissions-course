<?php

namespace Database\Factories;

use App\Enums\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    private string $clinicDefaultName = 'Clinic 123';

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    public function masterAdmin(): static
    {
        return $this->afterCreating(function (User $user) {
            $team = Team::create([
                'name' => 'Master Admin Team',
            ]);

            $user->update(['current_team_id' => $team->id]);

            setPermissionsTeamId($team->id);

            $user->assignRole(Role::MasterAdmin);
        });
    }

    public function clinicOwner(): static
    {
        return $this->afterCreating(function (User $user) {
            $team = Team::create([
                'name' => $this->clinicDefaultName,
            ]);

            $user->update(['current_team_id' => $team->id]);

            setPermissionsTeamId($team->id);

            $user->assignRole(Role::ClinicOwner);
        });
    }

    public function clinicAdmin(): static
    {
        return $this->afterCreating(function (User $user) {
            $team = Team::firstOrCreate(['name' => $this->clinicDefaultName]);

            $user->update(['current_team_id' => $team->id]);

            setPermissionsTeamId($team->id);

            $user->assignRole(Role::ClinicAdmin);
        });
    }

    public function doctor(): static
    {
        return $this->afterCreating(function (User $user) {
            $team = Team::firstOrCreate(['name' => $this->clinicDefaultName]);

            $user->update(['current_team_id' => $team->id]);

            setPermissionsTeamId($team->id);

            $user->assignRole(Role::Doctor);
        });
    }

    public function staff(): static
    {
        return $this->afterCreating(function (User $user) {
            $team = Team::firstOrCreate(['name' => $this->clinicDefaultName]);

            $user->update(['current_team_id' => $team->id]);

            setPermissionsTeamId($team->id);

            $user->assignRole(Role::Staff);
        });
    }

    public function patient(): static
    {
        return $this->afterCreating(function (User $user) {
            $team = Team::firstOrCreate(['name' => $this->clinicDefaultName]);

            $user->update(['current_team_id' => $team->id]);

            setPermissionsTeamId($team->id);

            $user->assignRole(Role::Patient);
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the model has two-factor authentication configured.
     */
    public function withTwoFactor(): static {}
}