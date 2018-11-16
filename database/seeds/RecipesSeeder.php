<?php

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Database\Seeder;

class RecipesSeeder extends Seeder
{
    public function run()
    {
        factory(Recipe::class, 310)->create([
            'user_id' => User::first()->id,
        ]);
    }
}
