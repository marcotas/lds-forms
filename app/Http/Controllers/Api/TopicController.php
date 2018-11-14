<?php

namespace App\Http\Controllers\Api;

use App\Filters\TopicFilters;
use App\Http\Controllers\Controller;
use App\Http\Requests\BulkDestroyRequest;
use App\Http\Requests\TopicRequest;
use App\Http\Resources\DataResource;
use App\Http\Resources\TopicResource;
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
        return TopicResource::collection(QueryBuilder::for(Topic::class)
            ->allowedSorts('name', 'id', 'date', 'created_at')
            ->filters($filters)
            ->orderBy('position')
            ->paginate($this->perPage($request)));
    }

    public function edit(Topic $topic)
    {
        return view('admin.topics.edit', compact('topic'));
    }

    public function store(TopicRequest $request)
    {
        return new TopicResource(Topic::create($request->validated()));
    }

    public function update(TopicRequest $request, Topic $topic)
    {
        $topic->update($request->validated());

        return new TopicResource($topic->fresh());
    }

    public function destroy(Topic $topic)
    {
        $topic->delete();
    }
}
