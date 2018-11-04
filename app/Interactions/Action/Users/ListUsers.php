<?php

namespace App\Interactions\Action\Users;

use App\Actions\Action;
use App\Filters\UsersFilters;
use App\Models\User;
use App\Traits\Paginations;
use Spatie\QueryBuilder\Filter;
use Spatie\QueryBuilder\QueryBuilder;

class ListUsers extends Action
{
    use Paginations;

    public $validations = [
        'filters' => 'required|object:' . UsersFilters::class,
    ];

    public function execute()
    {
        return QueryBuilder::for(User::class)
            ->allowedSorts('name', 'email', 'id')
            ->allowedFilters([
                Filter::exact('active'),
                Filter::exact('gender'),
                Filter::scope('with_trashed'),
                Filter::scope('only_trashed'),
            ])
            ->filters($this->filters);
    }
}
