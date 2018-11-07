<?php

namespace App\Http\Controllers\Topics;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Topic;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DistributeTopicsController extends Controller
{
    private $topicsWithoutDate;

    public function __invoke(Request $request)
    {
        DB::transaction(function () {
            $sunday = new Carbon('next sunday');

            $this->topicsWithoutDate = Topic::withoutDate()->get()->shuffle();

            while (!$this->topicsWithoutDate->isEmpty()) {
                $this->distributeTopicsOn($sunday);
                $sunday->addWeek(1);
                dump('topics without date is not empty', $this->topicsWithoutDate->count());
            }
        });
    }

    private function distributeTopicsOn(Carbon $date)
    {
        $topicsOfCurrentSunday = Topic::where('date', $date)->get();
        $existingPositions     = $topicsOfCurrentSunday->map->position->toArray();
        $remaingPositions      = collect(range(1, 3))->diff($existingPositions);

        $remaingPositions->each(function ($position) use ($date) {
            optional($this->topicsWithoutDate->pop())->update(compact('date', 'position'));
        });
    }
}
