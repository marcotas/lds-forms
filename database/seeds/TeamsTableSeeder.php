<?php

use App\Models\Team;
use Illuminate\Database\Seeder;
use App\Models\User;

class TeamsTableSeeder extends Seeder
{
    public function run()
    {
        $user = User::find(1);
        create(Team::class, ['name' => 'Gama I', 'owner_id' => $user]);
        create(Team::class, ['name' => 'Gama Centro', 'owner_id' => $user]);

        Team::each(function ($team) use ($user) {
            $user->joinTeam($team, 'owner');
        });

        $team = Team::find(1);
        User::whereNotIn('id', [1])->each(function ($user) use ($team) {
            $user->joinTeam($team);
        });
    }
}
