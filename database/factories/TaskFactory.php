<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomAssignee = collect([
            User::factory()->doctor(),
            User::factory()->staff(),
        ])->random();

        return [
            'name' => fake()->text(30),
            'due_date' => now()->addDays(rand(1, 100)),
            'assigned_to_user_id' => $randomAssignee,
            'patient_id' => User::factory()->patient(),
        ];
    }
}
