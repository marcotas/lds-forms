<?php

namespace App\Http\Controllers;

use App\Http\Requests\Speeches\StoreRequest;
use App\Http\Requests\Speeches\UpdateRequest;
use App\Http\Resources\DataResource;
use App\Models\Speech;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Spatie\QueryBuilder\QueryBuilder;

class SpeechController extends Controller
{
    public function list(Request $request)
    {
        $speeches = DataResource::collection(
            QueryBuilder::for(Speech::ofTeam(team()))
                ->orderBy('created_at', 'desc')
                ->search($request->search)
                ->paginate($request->get('perPage', 15))
        );

        if (request()->ajax()) {
            return $speeches;
        }

        $speeches = $speeches->toResponse($request)->getData(true);

        return view('speeches.list', compact('speeches'));
    }

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

        $speech = team()->speeches()->create(array_merge(
            compact('order'),
            collect($request->validated())->except('invited_at', 'confirmed_at')->toArray()
        ));

        if (false === $request->invited_at) {
            $speech->update(['invited_at' => null, 'invited_by' => null]);
        }

        if (false === $request->confirmed_at) {
            $speech->update(['confirmed_at' => null, 'confirmed_by' => null]);
        }

        if (true === $request->invited_at && !$speech->invitedBy) {
            $speech->update(['invited_at' => now(), 'invited_by' => user()->id]);
        }

        if (true === $request->confirmed_at && !$speech->confirmedBy) {
            $speech->update(['confirmed_at' => now(), 'confirmed_by' => user()->id]);
        }

        return $speech;
    }

    public function update(UpdateRequest $request, Speech $speech)
    {
        abort_unless($speech->sameTeamOf(user()), Response::HTTP_NOT_FOUND);

        $order = team()->speeches()->whereDate('date', $request->date)->count() + 1;
        $order = $speech->order ?? $order;
        $speech->update(array_merge(
            compact('order'),
            collect($request->validated())->except('invited_at', 'confirmed_at')->toArray()
        ));

        if (false === $request->invited_at) {
            $speech->update(['invited_at' => null, 'invited_by' => null]);
        }

        if (false === $request->confirmed_at) {
            $speech->update(['confirmed_at' => null, 'confirmed_by' => null]);
        }

        if (true === $request->invited_at && !$speech->invitedBy) {
            $speech->update(['invited_at' => now(), 'invited_by' => user()->id]);
        }

        if (true === $request->confirmed_at && !$speech->confirmedBy) {
            $speech->update(['confirmed_at' => now(), 'confirmed_by' => user()->id]);
        }

        return $speech->refresh();
    }

    public function destroy(Speech $speech)
    {
        abort_unless($speech->sameTeamOf(user()), Response::HTTP_NOT_FOUND);

        $speech->delete();
    }
}
