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
        $minute = $this->getNextMinute($request);

        return redirect()->to(route('minutes.show', ['minute' => $minute->id]));
    }

    public function nextForm(Request $request)
    {
        $minute = $this->getNextMinute($request);

        return redirect()->to(route('minutes.form', ['minute' => $minute->id]));
    }

    public function prev(Request $request)
    {
        $minute = $this->getPrevMinute($request);

        return redirect()->to(route('minutes.show', ['minute' => $minute->id]));
    }

    public function prevForm(Request $request)
    {
        $minute = $this->getPrevMinute($request);

        return redirect()->to(route('minutes.form', ['minute' => $minute->id]));
    }

    public function form(Minute $minute)
    {
        $minute = json_encode(new MinuteResource($minute));

        return view('minutes.form', compact('minute'));
    }

    public function show(Minute $minute)
    {
        $minute = json_encode(new MinuteResource($minute));

        return view('minutes.show', compact('minute'));
    }

    private function getNextMinute(Request $request)
    {
        $this->validate($request, [
            'from' => 'sometimes|date',
        ]);

        $from = $request->from;

        return Minute::firstOrCreate([
            'date' => (new Carbon($from))->modify('next sunday')->toDateString()
        ]);
    }

    private function getPrevMinute(Request $request)
    {
        $this->validate($request, [
            'from' => 'sometimes|date',
        ]);

        return Minute::firstOrCreate([
            'date' => (new Carbon($request->from))->modify('previous sunday')->toDateString()
        ]);
    }
}
