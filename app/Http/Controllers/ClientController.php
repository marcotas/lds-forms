<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientBulkDestroyRequest;
use App\Http\Requests\Clients\StoreRequest;
use App\Http\Requests\Clients\UpdateRequest;
use App\Http\Resources\DataResource;
use App\Models\Client;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        abort_if(user()->cant('manage.clients', team()), 404);

        if (!$request->ajax()) {
            return view('clients.index');
        }

        return DataResource::collection(
            QueryBuilder::for(Client::class)
                ->allowedFilters(
                    Filter::scope('withTrashed'),
                    Filter::scope('onlyTrashed')
                )
                ->ofTeam(team())
                ->search($request->search)
                ->paginate()
        );
    }

    public function store(StoreRequest $request)
    {
        return DataResource::make(team()->clients()->create($request->validated()));
    }

    public function show(Client $client)
    {
        return DataResource::make($client);
    }

    public function update(UpdateRequest $request, Client $client)
    {
        abort_unless($client->sameTeamOf(user()), Response::HTTP_NOT_FOUND);
        $client->update($request->validated());

        return DataResource::make($client);
    }

    public function destroy(Client $client)
    {
        abort_if(team()->id !== $client->team_id, Response::HTTP_BAD_REQUEST);
        abort_unless($client->sameTeamOf(user()), Response::HTTP_NOT_FOUND);
        $client->delete();
    }

    public function forceDestroy($id)
    {
        $client = Client::withTrashed()->findOrFail($id);

        abort_if(team()->id !== $client->team_id, Response::HTTP_BAD_REQUEST);
        abort_unless($client->sameTeamOf(user()), Response::HTTP_NOT_FOUND);

        $client->forceDelete();
    }

    public function restore($id)
    {
        $client = Client::withTrashed()->findOrFail($id);

        abort_if(team()->id !== $client->team_id, Response::HTTP_BAD_REQUEST);
        abort_unless($client->sameTeamOf(user()), Response::HTTP_NOT_FOUND);

        $client->restore();

        return DataResource::make($client);
    }

    public function bulkDestroy(ClientBulkDestroyRequest $request)
    {
        Client::destroy($request->ids);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
