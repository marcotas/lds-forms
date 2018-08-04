<?php

namespace App\Traits;

use Illuminate\Validation\ValidationException;

trait ExecuteActions
{
    public function execute(string $action, array $params = [], $dangerous = true)
    {
        return $action::run($params, $dangerous);
    }
}
