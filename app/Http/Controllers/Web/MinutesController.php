<?php

namespace App\Http\Controllers\Web;

use App\Actions\Minutes\ListMinutes;
use App\Http\Controllers\Controller;
use App\Http\Resources\MinuteResource;
use App\Models\Minute;
use Illuminate\Http\Request;

class MinutesController extends Controller
{
    public function index(Request $request)
    {
        $minutes = json_encode(MinuteResource::collection(
            $this->execute(ListMinutes::class, ['request' => $request])
        ));

        return view('minutes.index', compact('minutes'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Minute $minute)
    {
        $minute = json_encode(new MinuteResource($minute));

        return view('minutes.show', compact('minute'));
    }

    public function edit(Minute $minute)
    {
        //
    }

    public function update(Request $request, Minute $minute)
    {
        //
    }

    public function destroy(Minute $minute)
    {
        //
    }
}
