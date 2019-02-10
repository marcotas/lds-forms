<?php

namespace App\Http\Requests\Services;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return user()->can('manage.services', team());
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'max:255',
                Rule::unique('services')->where(function ($service) {
                    return $service->whereTeamId(team()->id);
                })->ignore($this->route('service'))
            ],
            'description' => 'sometimes|nullable',
            'price'       => 'required|numeric|min:0',
            'commission'  => 'required|numeric|min:0|max:100',
        ];
    }
}
