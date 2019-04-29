<?php

namespace App\Http\Controllers\Speeches;

use App\Http\Controllers\Controller;
use App\Http\Requests\Speeches\ImportAllRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Speech;

class ImportAllController extends Controller
{
    public function __invoke(ImportAllRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $speeches = collect($request->speeches)->mapInto(Speech::class);
            team()->speeches()->saveMany($speeches);

            return team()->speeches;
        });
    }
}
