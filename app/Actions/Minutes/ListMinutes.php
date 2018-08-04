<?php

namespace App\Actions\Minutes;

use App\Actions\Action;
use App\Models\Minute;
use App\Traits\Paginations;
use Illuminate\Http\Request;

class ListMinutes extends Action
{
    use Paginations;

    public $validations = [
        'request' => 'required|object:' . Request::class
    ];

    public function execute()
    {
        return Minute::latest('date')->paginate($this->perPage($this->request));
    }
}
