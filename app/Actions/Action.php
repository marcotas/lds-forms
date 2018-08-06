<?php

namespace App\Actions;

use Illuminate\Support\Facades\Validator;
use ZachFlower\EloquentInteractions\Interaction;

abstract class Action extends Interaction
{
    public function __construct($params = [])
    {
        $this->params = $params;

        $validations = method_exists($this, 'validations') ? $this->validations() : $this->validations;

        $this->validator = Validator::make(
            $params,
            $validations,
            array_merge($this->messages(), ['object' => 'The :attribute object type is invalid.']),
            $this->attributes()
        );
    }

    protected function compose(string $action, array $params, $actionName = 'base')
    {
        $outcome = $action::run($params);

        if (!$outcome->valid) {
            foreach ($outcome->errors->toArray() as $field => $errors) {
                foreach ($errors as $error) {
                    $this->validator->errors()->add("{$actionName}.{$field}", $error);
                }
            }
        }

        return $outcome;
    }

    protected function messages()
    {
        return [];
    }

    protected function attributes()
    {
        return [];
    }
}
