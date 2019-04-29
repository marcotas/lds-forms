<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\DataResource;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\User;
use App\Http\Requests\Members\CreateRequest;

class MemberController extends Controller
{
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

    public function store(CreateRequest $request)
    {
        return team()->users()->create($request->validated());
    }
}
