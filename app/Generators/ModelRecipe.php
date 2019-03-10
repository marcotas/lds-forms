<?php

namespace App\Generators;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class ModelRecipe extends Recipe
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $fields;

    public function __construct($model, $fields, Command $command)
    {
        parent::__construct($command);
        $this->model   = $model;
        $this->fields  = collect($fields);
    }

    public function getFieldNames() : Collection
    {
        return $this->getFields()->map->name;
    }

    public function getFields() : Collection
    {
        return $this->fields->mapInto(Field::class);
    }
}
