<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        create(User::class, ['email' => 'admin@mail.com', 'name' => 'Admin']);
        create(User::class, ['email' => 'marco@mail.com', 'name' => 'Marco Túlio']);
        create(User::class, ['email' => 'victor@mail.com', 'name' => 'Victor']);
        create(User::class, ['email' => 'professional@mail.com', 'name' => 'Professional']);
    }
}
