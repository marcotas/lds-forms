<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        create(User::class, ['email' => 'marcotulio.avila@gmail.com', 'name' => 'Marco Túlio']);
        // create(User::class, [], 30);
    }
}
