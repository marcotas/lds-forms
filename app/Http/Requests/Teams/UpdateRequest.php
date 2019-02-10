<?php

namespace App\Http\Requests\Teams;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return user()->can('manage.teams');
    }

    public function rules()
    {
        return [
            'name' => 'required',
        ];
    }
}
