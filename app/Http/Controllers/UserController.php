<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Spatie\Permission\Models\Role as RoleModel;

class UserController extends Controller
{
    #[Authorize('viewAny', User::class)]
    public function index(): View
    {
        $users = User::with('roles')
            ->whereHas('roles', function (Builder $query) {
                return $query->whereIn('name', [Role::ClinicAdmin->value, Role::Doctor->value, Role::Staff->value]);
            })
            ->whereRelation('teams', 'team_id', '=', Auth::user()->current_team_id)
            ->get();

        return view('users.index', compact('users'));
    }

    #[Authorize('create', User::class)]
    public function create(): View
    {
        $roles = RoleModel::whereIn('name', [Role::ClinicAdmin->value, Role::Doctor->value, Role::Staff->value])
            ->pluck('name', 'id');

        return view('users.create', compact('roles'));
    }

    #[Authorize('create', User::class)]
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $user = User::create($request->except('role_id'));

        $user->assignRole($request->integer('role_id'));

        return redirect()->route('users.index');
    }
}
