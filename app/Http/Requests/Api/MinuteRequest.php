<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class MinuteRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'date'              => 'required|date',
            'presided_by'       => 'nullable',
            'directed_by'       => 'nullable',
            'receptionist'      => 'nullable',
            'conductor'         => 'nullable',
            'pianist'           => 'nullable',
            'attendance'        => 'nullable',
            'ward'              => 'nullable',
            'stake'             => 'nullable',
            'welcome'           => 'nullable',
            'announcement'      => 'nullable',
            'first_hymn'        => 'nullable',
            'last_hymn'         => 'nullable',
            'sacrament_hymn'    => 'nullable',
            'intermediate_hymn' => 'nullable',
            'first_prayer'      => 'nullable',
            'last_prayer'       => 'nullable',
            'callings'          => 'nullable',
            'confirmations'     => 'nullable',
            'baby_blessings'    => 'nullable',
            'ordinances'        => 'nullable',
            'comments'          => 'nullable',
            'first_speaker'     => 'nullable',
            'second_speaker'    => 'nullable',
            'third_speaker'     => 'nullable',
        ];
    }
}
