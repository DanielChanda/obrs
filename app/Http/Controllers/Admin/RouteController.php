<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Route;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $routes = Route::with('operator')->latest()->paginate(15);
        return view('admin.routes.index', compact('routes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $operators = \App\Models\User::where('role', 'operator')
            ->get()
            ->pluck('name', 'id');

        return view('admin.routes.create', compact('operators'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'origin' => 'required|string|max:100',
            'destination' => 'required|string|max:100|different:origin',
            'distance' => 'nullable|integer|min:1|max:5000',
            'operator_id' => 'required|exists:users,id',
        ]);

        $route = \App\Models\Route::create($request->only(['origin','destination','distance','operator_id']));

        return redirect()->route('admin.routes.show', ['route' => $route])->with('success', 'Route created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Route $route)
    {
        
        $route->load('schedules.bus');
        
        $stats = [
            'totalSchedules' => $route->schedules->count(),
            'activeSchedules' => $route->schedules()->where('status', 'scheduled')->count(),
            'upcomingSchedules' => $route->schedules()
                ->where('status', 'scheduled')
                ->where('departure_time', '>', now())
                ->count(),
        ];

        return view('admin.routes.show', compact('route', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Route $route)
    {
        return view('admin.routes.edit', compact('route'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Route $route)
    {

        $request->validate([
            'origin' => 'required|string|max:100',
            'destination' => 'required|string|max:100|different:origin',
            'distance' => 'nullable|integer|min:1|max:5000',
        ]);

        $route->update($request->all());

        return redirect()->route('admin.routes.show',['route' => $route])
            ->with('success', 'Route updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Route $route)
    {
        //get all schedules on this route
        $schedules = $route->schedules;

        //delete each of the schedules
        foreach($schedules as $schedule){
            $schedule->delete();
        }

        //delete the route
        $route->delete();
        return redirect()->route('admin.routes.index')->with('success', 'route deleted successfully');
    }
}