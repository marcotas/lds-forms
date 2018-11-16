<?php

namespace App\Actions\Recipes;

use App\Actions\Action;
use App\Models\Recipe;

class BulkDestroy extends Action
{
    public $validations = [
        'ids' => 'required|array',
    ];

    public function execute()
    {
        return Recipe::destroy($this->ids);
    }
}
