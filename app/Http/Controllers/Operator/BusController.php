<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusController extends Controller {
    public function index() {
        $buses = Bus::where('operator_id', Auth::id())->get();
        return view('operator.buses.index', compact('buses'));
    }

    public function create() {
        return view('operator.buses.create');
    }

    public function store(Request $request) {
        $request->validate([
            'bus_number' => 'required|unique:buses',
            'capacity' => 'required|integer',
        ]);

        Bus::create([
            'operator_id' => Auth::id(),
            'bus_number' => $request->bus_number,
            'bus_type' => $request->bus_type,
            'capacity' => $request->capacity,
        ]);

        return redirect()->route('operator.buses.index');
    }
}
