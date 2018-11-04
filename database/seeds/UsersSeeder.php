<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $numbers = collect(range(0, 99))->shuffle();

        $user = factory(User::class)->create([
            'name'     => 'Marco Túlio',
            'email'    => 'marcotulio.avila@gmail.com',
            'gender'   => 'male',
            'password' => bcrypt('123456'),
        ]);

        $user->addMediaFromUrl("https://randomuser.me/api/portraits/men/{$numbers->pop()}.jpg")
            ->toMediaCollection('avatar');

        $users = factory(User::class, 99)->create();
        $users->each(function ($user) use (&$numbers) {
            $gender = $user->gender === 'male' ? 'men' : 'women';
            $user->addMediaFromUrl("https://randomuser.me/api/portraits/{$gender}/{$numbers->pop()}.jpg")
                ->toMediaCollection('avatar');
        });
    }
}
