<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TaskController extends Controller
{
    #[Authorize('viewAny', Task::class)]
    public function index(): View
    {
        $tasks = Task::with('assignee', 'patient')->get();

        return view('tasks.index', compact('tasks'));
    }

    #[Authorize('create', Task::class)]
    public function create(): View
    {
        $assignees = User::whereRelation('roles', 'name', '=', Role::Doctor->value)
            ->orWhereRelation('roles', 'name', '=', Role::Staff->value)
            ->whereRelation('teams', 'team_id', '=', Auth::user()->current_team_id)
            ->pluck('name', 'id');

        $patients = User::whereRelation('roles', 'name', '=', Role::Patient->value)
            ->whereRelation('teams', 'team_id', '=', Auth::user()->current_team_id)
            ->pluck('name', 'id');

        return view('tasks.create', compact('assignees', 'patients'));
    }

    #[Authorize('create', Task::class)]
    public function store(Request $request): RedirectResponse
    {
        Task::create($request->only('name', 'due_date', 'assigned_to_user_id', 'patient_id') + [
            'team_id' => Auth::user()->current_team_id,
        ]);

        return redirect()->route('tasks.index');
    }

    #[Authorize('update', 'task')]
    public function edit(Task $task): View
    {
        $assignees = User::whereRelation('roles', 'name', '=', Role::Doctor->value)
            ->orWhereRelation('roles', 'name', '=', Role::Staff->value)
            ->whereRelation('teams', 'team_id', '=', Auth::user()->current_team_id)
            ->pluck('name', 'id');

        $patients = User::whereRelation('roles', 'name', '=', Role::Patient->value)
            ->whereRelation('teams', 'team_id', '=', Auth::user()->current_team_id)
            ->pluck('name', 'id');

        return view('tasks.edit', compact('task', 'assignees', 'patients'));
    }

    #[Authorize('update', 'task')]
    public function update(Request $request, Task $task): RedirectResponse
    {
        $task->update($request->only('name', 'due_date', 'assigned_to_user_id', 'patient_id'));

        return redirect()->route('tasks.index');
    }

    #[Authorize('delete', 'task')]
    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        return redirect()->route('tasks.index');
    }
}
