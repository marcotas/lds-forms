<?php

namespace App\Http\Controllers;

use App\Http\Requests\Users\UserBulkDestroyRequest;
use App\Http\Requests\Users\UserCreateRequest;
use App\Http\Requests\Users\UserUpdateRequest;
use App\Http\Resources\DataResource;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function list(Request $request)
    {
        return redirect(route('admin'));
    }

    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('admin.users');
        }

        return DataResource::collection(
            QueryBuilder::for(User::class)
                ->whereHas('teams', function ($team) {
                    $team->whereId(team()->id);
                })
                ->search($request->search)
                ->orderBy('name')
                ->paginate($request->get('per_page', 15))
        );
    }

    public function create()
    {
        $permissions = Permission::all()->sortBy('label')->values();

        return view('users.create', compact('permissions'));
    }

    public function edit(User $user)
    {
        $user->append('specific_permissions', 'role_permissions');
        $user->load('roles.team', 'roles.specificPermissions', 'teams');
        $permissions = Permission::all()->sortBy('label')->values();

        return view('users.edit', compact('user', 'permissions'));
    }

    public function store(UserCreateRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $user       = User::create($request->validated());
            $user->role = Role::globals()->first();

            return DataResource::make($user);
        });
    }

    public function show(User $user)
    {
        $user->load('roles');

        return DataResource::make($user);
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        $user->update($request->validated());

        return DataResource::make($user);
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    public function bulkDestroy(UserBulkDestroyRequest $request)
    {
        User::destroy($request->ids);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
