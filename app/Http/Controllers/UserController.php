<?php

namespace App\Http\Controllers;

use App\Filters\UsersFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\DataResource;
use App\Http\Resources\UserResource;
use App\Http\Traits\BulkDestroy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    use BulkDestroy;

    protected $model = User::class;

    public function index(Request $request, UsersFilters $filters)
    {
        $query = QueryBuilder::for(User::class)
            ->allowedSorts('name', 'email', 'id')
            ->allowedFields('id', 'name')
            ->allowedFilters([
                Filter::exact('active'),
                Filter::exact('gender'),
                Filter::scope('with_trashed'),
                Filter::scope('only_trashed'),
            ])
            ->whereWardId($this->currentUser()->ward_id)
            ->filters($filters)
            ->paginate($this->perPage($request));

        return DataResource::collection($query);
    }

    public function suggestions(Request $request, UsersFilters $filters)
    {
        return DataResource::collection(User::whereWardId($this->currentUser()->ward_id)
            ->filters($filters)
            ->orderBy('name')
            ->active()
            ->whereDoesntHave('topics', function ($topic) {
                return $topic->future(now()->subMonth(3));
            })
            ->get());
    }

    public function show(Request $request, User $user)
    {
        return new DataResource($user);
    }

    public function store(UserRequest $request)
    {
        $user = User::create($request->validated());

        if ($request->has('new_avatar')) {
            $user->addMediaFromRequest('new_avatar')->toMediaCollection('avatar');
        }

        return $user;
    }

    public function update(UserRequest $request, User $user)
    {
        $user->update($request->validated());

        if ($request->has('new_avatar')) {
            $user->addMediaFromRequest('new_avatar')->toMediaCollection('avatar');
        }

        return $user->fresh();
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
