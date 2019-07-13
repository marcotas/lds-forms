<?php

namespace App\Http\Requests\Speeches;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title'        => 'required',
            'link'         => 'required|url',
            'author'       => 'sometimes|max:255',
            'date'         => 'sometimes|nullable|date',
            'speaker_id'   => 'sometimes|nullable|exists:users,id',
            'order'        => 'nullable|numeric|min:1',
            'duration'     => 'required|numeric|min:5',
            'invited_at'   => 'sometimes',
            'confirmed_at' => 'sometimes',
        ];
    }

    public function attributes()
    {
        return [
            'link' => 'url do discurso',
        ];
    }
}
