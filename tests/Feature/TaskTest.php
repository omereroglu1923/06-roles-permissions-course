<?php

use App\Models\Task;
use App\Models\User;
use function Pest\Laravel\actingAs;

it('allows administrator to access create task page', function () {
    $user = User::factory()->admin()->create();

    actingAs($user)
        ->get(route('tasks.create'))
        ->assertOk();
});

it('does not allow other users to access create task page', function (User $user) {
    actingAs($user)
        ->get(route('tasks.create'))
        ->assertForbidden();
})->with([
    fn() => User::factory()->user()->create(),
    fn() => User::factory()->manager()->create(),
]);

it('allows administrator and manager to enter update page for any task', function (User $user) {
    $task = Task::factory()->create(['user_id' => User::factory()->create()->id]);

    actingAs($user)
        ->get(route('tasks.edit', $task))
        ->assertOk();
})->with([
    fn() => User::factory()->admin()->create(),
    fn() => User::factory()->manager()->create(),
]);

it('allows administrator and manager to update any task', function (User $user) {
    $task = Task::factory()->create(['user_id' => User::factory()->create()->id]);

    actingAs($user)
        ->put(route('tasks.update', $task), ['name' => 'updated task name'])
        ->assertRedirect();

    expect($task->refresh()->name)->toBe('updated task name');
})->with([
    fn() => User::factory()->admin()->create(),
    fn() => User::factory()->manager()->create(),
]);

it('allows user to update their own task', function () {
    $user = User::factory()->user()->create();
    $task = Task::factory()->create(['user_id' => $user->id]);

    actingAs($user)
        ->put(route('tasks.update', $task), ['name' => 'updated task name']);

    expect($task->refresh()->name)->toBe('updated task name');
});

it('does not allow user to update other users task', function () {
    $user = User::factory()->user()->create();
    $task = Task::factory()->create(['user_id' => User::factory()->create()->id]);

    actingAs($user)
        ->put(route('tasks.update', $task), ['name' => 'updated task name'])
        ->assertNotFound();
});

it('allows administrator to delete task', function () {
    $task = Task::factory()->create(['user_id' => User::factory()->create()->id]);
    $user = User::factory()->admin()->create();

    actingAs($user)
        ->delete(route('tasks.destroy', $task))
        ->assertRedirect();

    expect(Task::count())->toBe(0);
});

it('does not allow simple user to delete other tasks', function () {
    $user = User::factory()->user()->create();
    $task = Task::factory()->create(['user_id' => User::factory()->create()->id]);

    actingAs($user)
        ->delete(route('tasks.destroy', $task))
        ->assertNotFound();
});

it('does not allow manager to delete other tasks', function () {
    $user = User::factory()->manager()->create();
    $task = Task::factory()->create(['user_id' => User::factory()->create()->id]);

    actingAs($user)
        ->delete(route('tasks.destroy', $task))
        ->assertForbidden();
});

it('user is unable to see other people tasks', function () {
    $user = User::factory()->user()->create();
    $task = Task::factory()->create(['user_id' => $user->id]);

    $user2 = User::factory()->create();
    $otherTasks = Task::factory()->create(['user_id' => $user2->id]);

    actingAs($user)
        ->get(route('tasks.index'))
        ->assertSeeText($task->name)
        ->assertDontSeeText($otherTasks->name);
});
