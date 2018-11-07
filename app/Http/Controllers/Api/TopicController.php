<?php

namespace App\Http\Controllers\Api;

use App\Filters\TopicFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\BulkDestroyRequest;
use App\Http\Requests\TopicRequest;
use App\Http\Resources\DataResource;
use App\Http\Traits\BulkDestroy;
use App\Models\Topic;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;

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

    public function edit(Topic $topic)
    {
        return view('admin.topics.edit', compact('topic'));
    }

    public function store(TopicRequest $request)
    {
        return Topic::create($request->validated());
    }

    public function update(TopicRequest $request, Topic $topic)
    {
        $topic->update($request->validated());

        return $topic->fresh();
    }

    public function destroy($id)
    {
        //
    }
}
