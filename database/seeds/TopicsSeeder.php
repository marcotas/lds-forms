<?php

use Illuminate\Database\Seeder;
use App\Models\Topic;

class TopicsSeeder extends Seeder
{
    public function run()
    {
        factory(Topic::class, 50)->create();
    }
}
