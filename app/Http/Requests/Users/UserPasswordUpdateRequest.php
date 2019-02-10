<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserPasswordUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::id() === $this->user->id;
    }

    public function rules()
    {
        return [
            'current_password' => 'required',
            'password'         => 'required|min:6|confirmed',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!password_verify($this->current_password, $this->user->password)) {
                $validator->errors()->add('current_password', 'A senha atual está incorreta.');
            }
        });

        return;
    }

    public function attributes()
    {
        return [
            'current_password' => 'senha atual',
        ];
    }
}
