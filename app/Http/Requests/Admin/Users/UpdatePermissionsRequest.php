<?php

namespace App\Http\Requests\Admin\Users;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePermissionsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'permissions'                 => 'nullable|array',
            'permissions.*.id'            => 'required|exists:permissions,id',
            'permissions.*.pivot.team_id' => 'sometimes|nullable|exists:teams,id',
        ];
    }
}
