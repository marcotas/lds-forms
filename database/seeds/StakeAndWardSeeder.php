<?php

use Illuminate\Database\Seeder;
use App\Models\Stake;
use App\Models\Ward;
use App\Models\User;

class StakeAndWardSeeder extends Seeder
{
    public function run()
    {
        $presidents = User::all()->shuffle()->take(Stake::count() + 3);
        $bishops    = User::whereNotIn('id', $presidents->map->id)->get()->shuffle()->take(Ward::count() + 3);

        $stakes = factory(Stake::class, 3)->create()->each(function ($stake) use ($presidents) {
            $stake->update(['president_id' => $presidents->pop()->id]);
        });

        factory(Ward::class, 3)->create()->each(function ($ward) use ($stakes, $bishops) {
            $ward->update([
                'stake_id'  => $stakes->pop()->id,
                'bishop_id' => $bishops->pop()->id,
            ]);
        });
    }
}
