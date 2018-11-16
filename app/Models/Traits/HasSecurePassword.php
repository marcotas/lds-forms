<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Hash;

trait HasSecurePassword
{
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = Hash::make($value);
        }
    }
}
