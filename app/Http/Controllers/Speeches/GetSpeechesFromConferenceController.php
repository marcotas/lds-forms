<?php

namespace App\Http\Controllers\Speeches;

use App\Http\Controllers\Controller;
use App\Http\Requests\Speeches\GetSpeechesFromConferenceRequest;
use App\Facades\Site;

class GetSpeechesFromConferenceController extends Controller
{
    public function __invoke(GetSpeechesFromConferenceRequest $request)
    {
        return Site::setUrl($request->link)->getSpeeches();
    }
}
