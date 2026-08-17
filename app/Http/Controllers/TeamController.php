<?php

namespace App\Http\Controllers;

use App\Enums\Role as RoleEnum;
use App\Http\Requests\StoreTeamRequest;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Attributes\Controllers\Authorize;
use Illuminate\View\View;
use Spatie\Permission\Models\Role as RoleModel;
use Symfony\Component\HttpFoundation\Response;

class TeamController extends Controller
{
    #[Authorize('viewAny', Team::class)]
    public function index(): View
    {
        $teams = Team::where('name', '!=', 'Master Admin Team')->paginate();

        return view('teams.index', compact('teams'));
    }

    #[Authorize('create', Team::class)]
    public function create(): View
    {
        $users = User::whereRelation('rolesWithoutTeam', 'name', '=', RoleEnum::ClinicOwner->value)
            ->pluck('name', 'id');

        return view('teams.create', compact('users'));
    }

    #[Authorize('create', Team::class)]
    public function store(StoreTeamRequest $request): RedirectResponse
    {
        $team = Team::create(['name' => $request->input('clinic_name')]);

        if ($request->integer('user_id') > 0) {
            $user = User::find($request->integer('user_id'));
            $user->update(['current_team_id' => $team->id]);
        } else {
            $user = User::create($request->only(['name', 'email', 'password'])
                + ['current_team_id' => $team->id]);
        }

        $user->teams()
            ->attach($team->id, [
                'model_type' => User::class,
                'role_id' => RoleModel::where('name', RoleEnum::ClinicOwner->value)->first()->id,
            ]);

        return redirect()->route('teams.index');
    }

    #[Authorize('changeTeam', Team::class)]
    public function changeCurrentTeam(int $teamId): RedirectResponse
    {
        $team = auth()->user()->teams()->findOrFail($teamId);

        if (! auth()->user()->belongsToTeam($team)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        auth()->user()->update(['current_team_id' => $team->id]);
        setPermissionsTeamId($team->id);
        auth()->user()->unsetRelation('roles')->unsetRelation('permissions');

        return redirect(route('dashboard'), Response::HTTP_SEE_OTHER);
    }
}
