<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        factory(User::class)->create([
            'name'     => 'Marco Túlio',
            'email'    => 'marcotulio.avila@gmail.com',
            'password' => bcrypt('123456'),
        ]);
    }
}
