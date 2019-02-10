<?php

namespace App\Http\Controllers\Teams;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;

class SwitchTeamController extends Controller
{
    public function __invoke(Request $request, Team $team)
    {
        user()->switchToTeam($team);

        return back();
    }
}
