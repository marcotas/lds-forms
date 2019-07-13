<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubscribeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $existingUser = User::where('email', $this->email)->first();

        return [
            'team_name' => [
                'required',
                'max:255',
                Rule::unique('teams', 'name')
                    ->where(function (Builder $team) use ($existingUser) {
                        return $team->where('owner_id', optional($existingUser)->id);
                    }),
            ],
            'name'  => 'required|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
            'gender'   => 'required|in:male,female',
            'password' => 'required|min:6|confirmed'
        ];
    }

    public function messages()
    {
        return [
            'team_name.unique' => 'Esse usuário já é dono de uma ala com esse nome.',
        ];
    }
}
