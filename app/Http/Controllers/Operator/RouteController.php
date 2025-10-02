<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RouteController extends Controller
{
    public function index()
    {
        $routes = Route::where('operator_id', Auth::id())
            ->withCount('schedules')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('operator.routes.index', compact('routes'));
    }

    public function create()
    {
        return view('operator.routes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'origin' => 'required|string|max:100',
            'destination' => 'required|string|max:100|different:origin',
            'distance' => 'nullable|integer|min:1|max:5000',
        ]);

        // Check if route already exists for this operator (case insensitive)
        $existingRoute = Route::where('operator_id', Auth::id())
            ->whereRaw('LOWER(origin) = ?', [strtolower($request->origin)])
            ->whereRaw('LOWER(destination) = ?', [strtolower($request->destination)])
            ->first();

        if ($existingRoute) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['route' => 'You already have this route in your collection.']);
        }

        Route::create([
            'operator_id' => Auth::id(),
            'origin' => $request->origin,
            'destination' => $request->destination,
            'distance' => $request->distance,
        ]);

        return redirect()->route('operator.routes.index')
            ->with('success', 'Route created successfully!');
    }

    public function show(Route $route)
    {
        // Ensure the route belongs to the current operator
        $this->authorizeOperatorRoute($route);
        
        $route->load('schedules.bus');
        
        $stats = [
            'totalSchedules' => $route->schedules->count(),
            'activeSchedules' => $route->schedules()->where('status', 'scheduled')->count(),
            'upcomingSchedules' => $route->schedules()
                ->where('status', 'scheduled')
                ->where('departure_time', '>', now())
                ->count(),
        ];

        return view('operator.routes.show', compact('route', 'stats'));
    }

    public function edit(Route $route)
    {
        $this->authorizeOperatorRoute($route);
        return view('operator.routes.edit', compact('route'));
    }

    public function update(Request $request, Route $route)
    {
        $this->authorizeOperatorRoute($route);

        $request->validate([
            'origin' => 'required|string|max:100',
            'destination' => 'required|string|max:100|different:origin',
            'distance' => 'nullable|integer|min:1|max:5000',
        ]);

        // Check if route already exists for this operator (excluding current route)
        $existingRoute = Route::where('operator_id', Auth::id())
            ->where('id', '!=', $route->id)
            ->whereRaw('LOWER(origin) = ?', [strtolower($request->origin)])
            ->whereRaw('LOWER(destination) = ?', [strtolower($request->destination)])
            ->first();

        if ($existingRoute) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['route' => 'You already have this route in your collection.']);
        }

        $route->update($request->all());

        return redirect()->route('operator.routes.index')
            ->with('success', 'Route updated successfully!');
    }

    public function destroy(Route $route)
    {
        $this->authorizeOperatorRoute($route);

        // Check if route has schedules
        if ($route->schedules()->exists()) {
            return redirect()->back()
                ->withErrors(['error' => 'Cannot delete route with existing schedules. Please delete schedules first.']);
        }

        $route->delete();

        return redirect()->route('operator.routes.index')
            ->with('success', 'Route deleted successfully!');
    }

    private function authorizeOperatorRoute(Route $route)
    {
        if ($route->operator_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this route.');
        }
    }
}