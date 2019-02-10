<?php

namespace App\Http\Controllers;

use App\Http\Requests\Services\StoreRequest;
use App\Http\Requests\Services\UpdateRequest;
use App\Http\Resources\DataResource;
use App\Models\Service;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('services.index');
        }

        return DataResource::collection(
            QueryBuilder::for(Service::class)
                ->allowedFilters([
                    Filter::scope('withTrashed'),
                    Filter::scope('onlyTrashed'),
                ])
                ->ofTeam(team())
                ->search($request->search)
                ->paginate()
        );
    }

    public function store(StoreRequest $request)
    {
        return DataResource::make(team()->services()->create($request->validated()));
    }

    public function update(UpdateRequest $request, Service $service)
    {
        abort_if(!$service->sameTeamOf(user()), 404);

        $service->update($request->validated());

        return DataResource::make($service);
    }

    public function destroy(Service $service)
    {
        abort_if(user()->cannot('manage.services'), Response::HTTP_FORBIDDEN);

        $service->delete();
    }

    public function forceDestroy($id)
    {
        $service = Service::withTrashed()->findOrFail($id);

        abort_if(user()->cannot('manage.services'), Response::HTTP_FORBIDDEN);
        abort_if(team()->id !== $service->team_id, Response::HTTP_NOT_FOUND);

        $service->forceDelete();
    }

    public function restore($id)
    {
        $service = Service::withTrashed()->findOrFail($id);
        abort_if(user()->cannot('manage.services'), Response::HTTP_FORBIDDEN);
        abort_if(
            team()->id !== $service->team_id,
            Response::HTTP_NOT_FOUND
        );

        $service->restore();
    }
}
