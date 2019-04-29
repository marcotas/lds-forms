<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\DataResource;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\Speech;
use Illuminate\Support\Carbon;
use App\Http\Requests\Speeches\StoreRequest;
use App\Http\Requests\Speeches\UpdateRequest;

class SpeechController extends Controller
{
    public function index(Request $request)
    {
        $hasNoDate = $request->has('no-date');
        $date      = $hasNoDate ? null : new Carbon($request->get('date', now()));

        $speeches = QueryBuilder::for(Speech::ofTeam(team()))
            ->when($date, function ($speech) use ($date) {
                $speech->whereMonth('date', $date);
            })
            ->when($hasNoDate, function ($speech) {
                $speech->whereNull('date');
            })
            ->orderBy('order')
            ->get();

        if (request()->ajax()) {
            return DataResource::collection($speeches);
        }

        return view('speeches.index', compact('speeches'));
    }

    public function store(StoreRequest $request)
    {
        $order = team()->speeches()->whereDate('date', $request->date)->count() + 1;
        $order = $request->order ?? $order;

        return team()->speeches()->create(array_merge(compact('order'), $request->validated()));
    }

    public function update(UpdateRequest $request, Speech $speech)
    {
        $order = team()->speeches()->whereDate('date', $request->date)->count() + 1;
        $order = $speech->order ?? $order;
        $speech->update(array_merge(compact('order'), $request->validated()));

        return $speech->refresh();
    }

    public function destroy(Speech $speech)
    {
        $speech->delete();
    }
}
