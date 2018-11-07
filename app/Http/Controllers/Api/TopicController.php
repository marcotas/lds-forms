<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Topic;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\Filter;
use App\Filters\TopicFilters;
use App\Http\Resources\DataResource;
use App\Http\Requests\BulkDestroyRequest;
use Illuminate\Http\Response;
use App\Http\Traits\BulkDestroy;

class TopicController extends Controller
{
    use BulkDestroy;

    protected $model = Topic::class;

    public function index(Request $request, TopicFilters $filters)
    {
        return DataResource::collection(QueryBuilder::for(Topic::class)
            ->allowedSorts('name', 'id', 'date')
            ->allowedFilters([
                Filter::exact('active'),
                Filter::exact('gender'),
                Filter::scope('with_trashed'),
                Filter::scope('only_trashed'),
            ])
            ->filters($filters)
            ->future()
            ->with('speaker')
            ->paginate($this->perPage($request)));
    }

    public function store(Request $request)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
