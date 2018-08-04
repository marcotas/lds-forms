<?php

use App\Models\Minute;
use Illuminate\Database\Seeder;

class MinutesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Minute::class, 10)->create();
    }
}
