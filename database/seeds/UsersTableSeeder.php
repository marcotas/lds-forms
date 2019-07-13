<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Team;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        create(User::class, ['email' => 'marcotulio.avila@gmail.com', 'name' => 'Marco Túlio']);
        $team = Team::first();
        create(User::class, [], 180)->each(function (User $user) use ($team) {
            $user->joinTeam($team);
        });
    }
}
