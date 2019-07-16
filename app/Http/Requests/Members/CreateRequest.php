<?php

namespace App\Http\Requests\Members;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'     => 'required|max:255',
            'email'    => 'sometimes|email|unique:users',
            'phone'    => 'sometimes|unique:users',
            'password' => 'sometimes|confirmed|min:6|max:64',
            'gender'   => 'sometimes|in:male,female',
        ];
    }
}
