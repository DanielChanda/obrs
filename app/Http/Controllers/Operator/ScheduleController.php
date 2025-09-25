<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Route;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller {
    public function index() {
        $schedules = Schedule::whereHas('bus', function($q) {
            $q->where('operator_id', Auth::id());
        })->with('bus', 'route')->get();

        return view('operator.schedules.index', compact('schedules'));
    }

    public function create() {
        $buses = Bus::where('operator_id', Auth::id())->get();
        $routes = Route::all();
        return view('operator.schedules.create', compact('buses', 'routes'));
    }

    public function store(Request $request) {
        $request->validate([
            'bus_id' => 'required',
            'route_id' => 'required',
            'departure_time' => 'required|date',
            'arrival_time' => 'required|date|after:departure_time',
            'fare' => 'required|numeric',
        ]);

        Schedule::create($request->all());
        return redirect()->route('operator.schedules.index');
    }
}
