<?php

namespace App\Http\Requests\Speeches;

use Illuminate\Foundation\Http\FormRequest;

class GetSpeechesFromConferenceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'link' => 'required|url',
        ];
    }
}
