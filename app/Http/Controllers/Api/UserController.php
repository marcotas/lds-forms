<?php

namespace App\Http\Controllers\Api;

use App\Filters\UsersFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Http\Traits\BulkDestroy;
use App\Interactions\Action\Users\ListUsers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    use BulkDestroy;

    protected $model = User::class;

    public function index(Request $request, UsersFilters $filters)
    {
        return $this->execute(ListUsers::class, compact('filters'))
            ->paginate($this->perPage($request));
    }

    public function store(UserRequest $request)
    {
        dd($request->validated());
    }

    public function show($id)
    {
        //
    }

    public function update(UserRequest $request, $id)
    {
        dd($request->validated());
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function forceDestroy(int $id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->forceDelete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function restore(int $id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return new UserResource($user->fresh());
    }
}
