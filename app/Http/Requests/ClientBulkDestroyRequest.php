<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientBulkDestroyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'ids' => 'required|array',
        ];
    }
}
