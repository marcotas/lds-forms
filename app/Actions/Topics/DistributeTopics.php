<?php

namespace App\Actions\Topics;

use App\Actions\Action;

class DistributeTopics extends Action
{
    public $validations = [
        'topics' => 'required|array',
    ];

    public function execute()
    {
        dd('jetete', $this->topics);
    }
}
