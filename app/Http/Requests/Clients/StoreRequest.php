<?php

namespace App\Http\Requests\Clients;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'  => 'required|max:64',
            'email' => ['sometimes', 'nullable', 'email', Rule::unique('clients')->where(function ($client) {
                $client->whereTeamId(team()->id);
            })],
            'phone'    => 'sometimes|nullable',
            'birthday' => 'sometimes|nullable|date',
        ];
    }
}
