<?php

namespace App\Http\Controllers\Web;

use App\Actions\Minutes\ListMinutes;
use App\Http\Controllers\Controller;
use App\Http\Resources\MinuteResource;
use App\Models\Minute;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MinutesController extends Controller
{
    public function index(Request $request)
    {
        return view('minutes.index');
    }

    public function next(Request $request)
    {
        $this->validate($request, [
            'from' => 'sometimes|date',
        ]);

        $from = $request->from;

        $minute = Minute::firstOrCreate([
            'date' => (new Carbon($from))->modify('next sunday')->toDateString()
        ]);

        return redirect()->to(route('minutes.show', ['minute' => $minute->id]));
    }

    public function prev(Request $request)
    {
        abort_unless($request->from, Response::HTTP_NOT_FOUND);

        $this->validate($request, [
            'from' => 'sometimes|date',
        ]);

        $minute = Minute::firstOrCreate([
            'date' => (new Carbon($request->from))->modify('previous sunday')->toDateString()
        ]);

        return redirect()->to(route('minutes.show', ['minute' => $minute->id]));
    }

    public function show(Minute $minute)
    {
        $minute = json_encode(new MinuteResource($minute));

        return view('minutes.show', compact('minute'));
    }
}
