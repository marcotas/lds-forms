<?php

namespace App\Http\Controllers;

use App\Http\Requests\Teams\UpdateRequest;
use App\Http\Resources\DataResource;
use App\Models\Team;
use Illuminate\Http\Response;

class TeamController extends Controller
{
    public function index()
    {
        return DataResource::collection(user()->teams);
    }

    public function show(Team $team)
    {
        abort_if(!user()->teams->contains($team), Response::HTTP_NOT_FOUND);
        $team->load('users.roles');

        return DataResource::make($team);
    }

    public function update(UpdateRequest $request, Team $team)
    {
        $team->update($request->validated());

        return DataResource::make($team);
    }
}
