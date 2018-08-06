<?php

namespace App\Http\Controllers\Api;

use App\Actions\Minutes\ListMinutes;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MinuteRequest;
use App\Http\Resources\MinuteResource;
use App\Models\Minute;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MinutesController extends Controller
{
    public function index(Request $request)
    {
        return MinuteResource::collection(
            $this->execute(ListMinutes::class, ['request' => $request])
        )->additional([
            'next_sunday' => now()->modify('next sunday')->toDateString(),
        ]);
    }

    public function store(MinuteRequest $request)
    {
        $minute = Minute::create($request->all());

        return new MinuteResource($minute);
    }

    public function show(Minute $minute)
    {
        return new MinuteResource($minute);
    }

    public function update(MinuteRequest $request, Minute $minute)
    {
        // dd($request->validated());
        $minute->update($request->validated());

        return new MinuteResource($minute->fresh());
    }

    public function destroy(Minute $minute)
    {
        $minute->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
