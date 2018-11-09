<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        if ($this->has('active')) {
            $input           = $this->all();
            $input['active'] = filter_var($input['active'], FILTER_VALIDATE_BOOLEAN);
            $this->replace($input);
        }

        return parent::getValidatorInstance();
    }

    public function rules()
    {
        $passwordRequired = $this->has('id') ? 'sometimes' : 'required';

        return [
            'name'       => 'required',
            'email'      => ['required', 'email', Rule::unique('users')->ignore($this->get('id'))],
            'gender'     => 'required|in:male,female',
            'password'   => $passwordRequired . '|min:6|max:64|confirmed',
            'active'     => 'sometimes|boolean',
            'new_avatar' => 'sometimes|file',
        ];
    }
}
