<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SubscribeRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class SubscribeController extends Controller
{
    public function __invoke(SubscribeRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $user = User::create($request->validated());
            $team = $user->teams()->create([
                'name'     => $request->team_name,
                'owner_id' => $user->id,
            ]);

            $user->joinTeam($team, Role::OWNER);
            $user->switchToTeam($team);

            event(new Registered($user));

            auth()->login($user);

            return $user->refresh();
        });
    }
}
