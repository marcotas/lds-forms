<?php

namespace App\Http\Controllers\Resources;

use App\Http\Requests\Members\CreateRequest;
use App\Models\User;
use Illuminate\Http\Response;
use MarcoT89\Bullet\Controllers\ResourceController;
use Spatie\QueryBuilder\QueryBuilder;

class MemberController extends ResourceController
{
    protected $model        = User::class;
    protected $defaultSorts = 'name';

    protected $only = ['index', 'store', 'update', 'destroy'];

    protected $requests = [
        'store' => CreateRequest::class,
    ];

    protected function getQuery()
    {
        return QueryBuilder::for(User::class)
            ->whereHas('teams', function ($team) {
                $team->whereId(team()->id);
            })
            ->with('roles');
    }

    protected function getStoreQuery($request)
    {
        return team()->users();
    }

    public function destroy($id)
    {
        $member = User::findOrFail($id);

        if ($member->teams()->count() === 1 && !$member->email) {
            $member->delete();

            return response()->json(null, Response::HTTP_NO_CONTENT);
        }

        $member->leaveTeam(team());

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
