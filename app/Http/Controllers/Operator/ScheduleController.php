<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Bus;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::whereHas('bus', function($q) {
                $q->where('operator_id', Auth::id());
            })
            ->with('bus', 'route')
            ->withCount('bookings')
            ->orderBy('departure_time', 'desc');

        //validate filters
        $request->validate([
            'date_from' => 'nullable|date',
            'date_to'   => 'nullable|date|after_or_equal:date_from',
            'status'    => 'nullable|in:all,scheduled,completed,cancelled',
        ]);

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('departure_time', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('departure_time', '<=', $request->date_to);
        }

        $schedules = $query->paginate(10);
        $filters = $request->only(['status', 'date_from', 'date_to']);

        return view('operator.schedules.index', compact('schedules', 'filters'));
    }

    public function create(Request $request)
    {
        $buses = Bus::where('operator_id', Auth::id())
            ->where('status', 'active')
            ->get();
            
        $routes = Route::where('operator_id', Auth::id())->get();

        // Pre-select bus or route if provided via query parameters
        $selectedBus = $request->get('bus_id');
        $selectedRoute = $request->get('route_id');

        return view('operator.schedules.create', compact('buses', 'routes', 'selectedBus', 'selectedRoute'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'route_id' => 'required|exists:routes,id',
            'departure_time' => 'required|date|after:now',
            'arrival_time' => 'required|date|after:departure_time',
            'fare' => 'required|numeric|min:1|max:1000',
        ]);

        // Verify the bus belongs to the current operator
        $bus = Bus::where('id', $request->bus_id)
            ->where('operator_id', Auth::id())
            ->firstOrFail();

        // Verify the route belongs to the current operator
        $route = Route::where('id', $request->route_id)
            ->where('operator_id', Auth::id())
            ->firstOrFail();

        // Check for schedule conflicts (same bus at overlapping times)
        $conflictingSchedule = Schedule::where('bus_id', $request->bus_id)
            ->where('status', 'scheduled')
            ->where(function($query) use ($request) {
                $query->whereBetween('departure_time', [$request->departure_time, $request->arrival_time])
                      ->orWhereBetween('arrival_time', [$request->departure_time, $request->arrival_time])
                      ->orWhere(function($q) use ($request) {
                          $q->where('departure_time', '<=', $request->departure_time)
                            ->where('arrival_time', '>=', $request->arrival_time);
                      });
            })
            ->first();

        if ($conflictingSchedule) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['bus_id' => 'This bus already has a scheduled trip during the selected time period.']);
        }

        Schedule::create([
            'bus_id' => $request->bus_id,
            'route_id' => $request->route_id,
            'departure_time' => $request->departure_time,
            'arrival_time' => $request->arrival_time,
            'fare' => $request->fare,
            'available_seats' => $bus->capacity,
            'status' => 'scheduled',
        ]);

        return redirect()->route('operator.schedules.index')
            ->with('success', 'Schedule created successfully!');
    }

    public function show(Schedule $schedule)
    {
        // Verify the schedule belongs to the current operator
        $this->authorizeOperatorSchedule($schedule);

        $schedule->load('bus', 'route', 'bookings.user');
        
        $stats = [
            'totalBookings' => $schedule->bookings->count(),
            'confirmedBookings' => $schedule->bookings()->where('status', 'confirmed')->count(),
            'revenue' => $schedule->bookings()
                ->where('payment_status', 'paid')
                ->get()
                ->sum(function($booking) use ($schedule) {
                    return $schedule->fare;
                }),
        ];

        return view('operator.schedules.show', compact('schedule', 'stats'));
    }

    public function edit(Schedule $schedule)
    {
        $this->authorizeOperatorSchedule($schedule);

        // Only allow editing if no bookings exist
        if ($schedule->bookings()->exists()) {
            return redirect()->route('operator.schedules.show', $schedule->id)
                ->withErrors(['error' => 'Cannot edit schedule with existing bookings.']);
        }

        $buses = Bus::where('operator_id', Auth::id())
            ->where('status', 'active')
            ->get();
            
        $routes = Route::where('operator_id', Auth::id())->get();

        return view('operator.schedules.edit', compact('schedule', 'buses', 'routes'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $this->authorizeOperatorSchedule($schedule);

        // Prevent editing if bookings exist
        if ($schedule->bookings()->exists()) {
            return redirect()->route('operator.schedules.show', $schedule->id)
                ->withErrors(['error' => 'Cannot update schedule with existing bookings.']);
        }

        $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'route_id' => 'required|exists:routes,id',
            'departure_time' => 'required|date|after:now',
            'arrival_time' => 'required|date|after:departure_time',
            'fare' => 'required|numeric|min:1|max:1000',
            'status' => 'required|in:scheduled,completed,cancelled',
        ]);

        // Verify the bus belongs to the current operator
        $bus = Bus::where('id', $request->bus_id)
            ->where('operator_id', Auth::id())
            ->firstOrFail();

        // Check for schedule conflicts (excluding current schedule)
        $conflictingSchedule = Schedule::where('bus_id', $request->bus_id)
            ->where('id', '!=', $schedule->id)
            ->where('status', 'scheduled')
            ->where(function($query) use ($request) {
                $query->whereBetween('departure_time', [$request->departure_time, $request->arrival_time])
                      ->orWhereBetween('arrival_time', [$request->departure_time, $request->arrival_time]);
            })
            ->first();

        if ($conflictingSchedule) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['bus_id' => 'This bus already has a scheduled trip during the selected time period.']);
        }

        $schedule->update([
            'bus_id' => $request->bus_id,
            'route_id' => $request->route_id,
            'departure_time' => $request->departure_time,
            'arrival_time' => $request->arrival_time,
            'fare' => $request->fare,
            'available_seats' => $bus->capacity, // Reset seats when bus changes
            'status' => $request->status,
        ]);

        return redirect()->route('operator.schedules.index')
            ->with('success', 'Schedule updated successfully!');
    }

    public function destroy(Schedule $schedule)
    {
        $this->authorizeOperatorSchedule($schedule);

        // Prevent deletion if bookings exist
        if ($schedule->bookings()->exists()) {
            return redirect()->back()
                ->withErrors(['error' => 'Cannot delete schedule with existing bookings.']);
        }

        $schedule->delete();

        return redirect()->route('operator.schedules.index')
            ->with('success', 'Schedule deleted successfully!');
    }

    public function cancel(Schedule $schedule)
    {
        $this->authorizeOperatorSchedule($schedule);

        if ($schedule->status === 'cancelled') {
            return redirect()->back()->withErrors(['error' => 'Schedule is already cancelled.']);
        }

        // Refund logic would go here for paid bookings
        $schedule->update(['status' => 'cancelled']);

        // Also cancel all associated bookings
        $schedule->bookings()->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Schedule cancelled successfully. All bookings have been cancelled.');
    }

    private function authorizeOperatorSchedule(Schedule $schedule)
    {
        if ($schedule->bus->operator_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this schedule.');
        }
    }
}