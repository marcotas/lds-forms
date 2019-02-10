<?php

use App\Models\Team;
use Illuminate\Database\Seeder;
use App\Models\User;

class TeamsTableSeeder extends Seeder
{
    public function run()
    {
        create(Team::class, ['name' => 'Gama I', 'owner_id' => User::find(1)]);
        create(Team::class, ['name' => 'Gama Centro', 'owner_id' => create(User::class)]);
    }
}
