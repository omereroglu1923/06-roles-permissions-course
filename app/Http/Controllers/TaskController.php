<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(): View
    {
        $tasks = Task::with('user')->get();

        return view('tasks.index', compact('tasks'));
    }

    public function create(): View
    {
        Gate::authorize('create-task');

        return view('tasks.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create-task');

        Task::create($request->only('name', 'due_date')
            + ['user_id' => Auth::id()]);

        return redirect()->route('tasks.index');
    }

    public function edit(Task $task): View
    {
        Gate::authorize('update-task', $task);

        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        Gate::authorize('update-task', $task);

        $task->update($request->only('name', 'due_date'));

        return redirect()->route('tasks.index');
    }

    public function destroy(Task $task): RedirectResponse
    {
        Gate::authorize('delete-task', $task);

        $task->delete();

        return redirect()->route('tasks.index');
    }
}
