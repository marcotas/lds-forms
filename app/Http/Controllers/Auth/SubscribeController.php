<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SubscribeRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Bouncer;
use App\Models\Speech;

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

            Bouncer::allow('superadmin')->everything();

            Bouncer::scope()->to($team->id);
            Bouncer::allow('owner')->to('*', Speech::class);
            Bouncer::allow('owner')->to('*', $team);

            $user->joinTeam($team, Role::OWNER);
            $user->switchToTeam($team);
            $user->assign('owner');

            event(new Registered($user));

            auth()->login($user);

            return $user->refresh();
        });
    }
}
