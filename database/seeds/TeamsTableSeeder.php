<?php

use App\Models\Team;
use Illuminate\Database\Seeder;
use App\Models\User;

class TeamsTableSeeder extends Seeder
{
    public function run()
    {
        $admin = User::find(1);
        create(Team::class, ['name' => 'Gama I', 'owner_id' => $admin]);
        // create(Team::class, ['name' => 'Gama Centro', 'owner_id' => $admin]);

        Team::each(function ($team) use ($admin) {
            $admin->joinTeam($team, 'owner');
        });

        // $team = Team::find(1);
        // User::where('id', '!=', $admin->id)->each(function ($user) use ($team) {
        //     $user->joinTeam($team);
        // });
    }
}
