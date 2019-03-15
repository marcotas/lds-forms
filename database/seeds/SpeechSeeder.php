<?php

use Illuminate\Database\Seeder;
use App\Models\Speech;
use Carbon\Carbon;

class SpeechSeeder extends Seeder
{
    public function run()
    {
        create(Speech::class, ['team_id' => 1, 'date' => now()->startOfMonth()->next(Carbon::SUNDAY)], rand(1, 3));
        create(Speech::class, ['team_id' => 1, 'date' => now()->startOfMonth()->next(Carbon::SUNDAY)->addWeek(1)], rand(1, 3));
        create(Speech::class, ['team_id' => 1, 'date' => now()->startOfMonth()->next(Carbon::SUNDAY)->addWeek(2)], rand(1, 3));
        create(Speech::class, ['team_id' => 1, 'date' => now()->startOfMonth()->next(Carbon::SUNDAY)->addWeek(3)], rand(1, 3));
        create(Speech::class, ['team_id' => 1, 'date' => now()->startOfMonth()->next(Carbon::SUNDAY)->addWeek(4)], rand(1, 3));
    }
}
