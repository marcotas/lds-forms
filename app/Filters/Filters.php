<?php

namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filters
{
    protected $request;
    protected $builder;
    protected $filters = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        $this->builder = $builder;
        foreach ($this->getFilters() as $filter => $value) {
            if ($this->methodExistsFor($filter)) {
                $method = method_exists($this, $filter) ? $filter : camel_case($filter);
                $this->$method($value);
            }
        }

        return $this->builder;
    }

    public function getFilters()
    {
        return array_filter($this->request->only($this->filters));
    }

    private function methodExistsFor($filter)
    {
        return method_exists($this, $filter) || method_exists($this, camel_case($filter));
    }
}
