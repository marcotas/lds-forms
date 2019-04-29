<?php

namespace App\Http\Requests\Speeches;

use Illuminate\Foundation\Http\FormRequest;

class ImportAllRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'speeches.*.title'     => 'required|max:255',
            'speeches.*.link'      => 'required|url',
            'speeches.*.author'    => 'sometimes|nullable',
            'speeches.*.image_url' => 'sometimes|nullable|url',
        ];
    }
}
