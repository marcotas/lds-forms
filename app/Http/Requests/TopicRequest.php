<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'link'     => 'required|url|max:1024',
            'user_id'  => 'nullable|exists:users,id',
            'position' => 'nullable|integer|min:1',
            'date'     => 'nullable|date'
        ];
    }
}
