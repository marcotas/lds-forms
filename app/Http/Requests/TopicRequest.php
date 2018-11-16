<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TopicRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'     => 'required|max:256',
            'link'     => [
                'required', 'url', 'max:1024',
                Rule::unique('topics')->ignore($this->get('id')),
            ],
            'user_id'      => 'nullable|exists:users,id',
            'position'     => 'nullable|integer|min:1',
            'date'         => 'nullable|date',
            'invited_at'   => 'nullable',
            'confirmed_at' => 'nullable',
        ];
    }
}
