<?php

namespace App\Actions\Minutes;

use App\Actions\Action;
use App\Filters\MinutesFilters;
use App\Models\Minute;
use App\Traits\Paginations;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class ListMinutes extends Action
{
    use Paginations;

    public $validations = [
        'request' => 'required|object:' . Request::class,
        'filters' => 'required|object:' . MinutesFilters::class,
    ];

    public function execute()
    {
        return QueryBuilder::for(Minute::query())
            ->allowedSorts('id', 'date')
            ->filters($this->filters)
            ->paginate($this->perPage($this->request));
    }
}
