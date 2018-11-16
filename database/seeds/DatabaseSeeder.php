<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(UsersSeeder::class);
        $this->call(MinutesSeeder::class);
        $this->call(RecipesSeeder::class);
        $this->call(StakeAndWardSeeder::class);
        $this->call(TopicsSeeder::class);
    }
}
