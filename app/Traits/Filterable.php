<?php

namespace App\Traits;

use App\Filters\Filters;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    public function scopeFilters(Builder $builder, Filters $filters)
    {
        return $filters->apply($builder);
    }
}
