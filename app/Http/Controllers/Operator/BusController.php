<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BusController extends Controller
{
    public function index()
    {
        $buses = Bus::where('operator_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('operator.buses.index', compact('buses'));
    }

    public function create()
    {
        return view('operator.buses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bus_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('buses')->where(function ($query) {
                    return $query->where('operator_id', Auth::id());
                })
            ],
            'bus_type' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1|max:100',
            'status' => 'required|in:active,inactive'
        ]);

        Bus::create([
            'operator_id' => Auth::id(),
            'bus_number' => $request->bus_number,
            'bus_type' => $request->bus_type,
            'capacity' => $request->capacity,
            'status' => $request->status,
        ]);

        return redirect()->route('operator.buses.index')
            ->with('success', 'Bus added successfully!');
    }

    public function show(Bus $bus)
    {
        // Ensure the bus belongs to the current operator
        $this->authorizeOperatorBus($bus);

        $bus->load('schedules.route');
        
        $stats = [
            'totalSchedules' => $bus->schedules->count(),
            'activeSchedules' => $bus->schedules()->where('status', 'scheduled')->count(),
            'totalBookings' => \App\Models\Booking::whereHas('schedule', function($query) use ($bus) {
                $query->where('bus_id', $bus->id);
            })->count(),
        ];

        return view('operator.buses.show', compact('bus', 'stats'));
    }

    public function edit(Bus $bus)
    {
        $this->authorizeOperatorBus($bus);
        return view('operator.buses.edit', compact('bus'));
    }

    public function update(Request $request, Bus $bus)
    {
        $this->authorizeOperatorBus($bus);

        $request->validate([
            'bus_number' => [
                'required',
                'string',
                'max:50',
                Rule::unique('buses')->where(function ($query) use ($bus) {
                    return $query->where('operator_id', Auth::id())->where('id', '!=', $bus->id);
                })
            ],
            'bus_type' => 'required|string|max:50',
            'capacity' => 'required|integer|min:1|max:100',
            'status' => 'required|in:active,inactive'
        ]);

        $bus->update($request->all());

        return redirect()->route('operator.buses.index')
            ->with('success', 'Bus updated successfully!');
    }

    public function destroy(Bus $bus)
    {
        $this->authorizeOperatorBus($bus);

        // Check if bus has active schedules
        if ($bus->schedules()->where('status', 'scheduled')->exists()) {
            return redirect()->back()
                ->withErrors(['error' => 'Cannot delete bus with active schedules. Please cancel schedules first.']);
        }

        $bus->delete();

        return redirect()->route('operator.buses.index')
            ->with('success', 'Bus deleted successfully!');
    }

    private function authorizeOperatorBus(Bus $bus)
    {
        if ($bus->operator_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this bus.');
        }
    }
}