<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $buses = Bus::with('operator')->latest()->paginate(15);
        return view('admin.buses.index', compact('buses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Bus $bus)
    {
        $bus->load('operator', 'schedules.route');
        return view('admin.buses.show', compact('bus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bus $bus)
    {
        $operators = User::where('role', 'operator')
            ->get()
            ->pluck('name', 'id'); // This will use your getNameAttribute accessor
        
        return view('admin.buses.edit', compact('bus', 'operators'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bus $bus)
    {
        $validated = $request->validate([
            'bus_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('buses')->ignore($bus->id),
            ],
            'bus_type' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'operator_id' => 'required|exists:users,id',
            'status' => 'required|in:active,inactive',
        ]);

        $bus->update($validated);

        return redirect()->route('admin.buses.show', $bus->id)
            ->with('success', 'Bus details updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bus $bus)
    {
        // Prevent deletion if the bus has associated schedules to maintain data integrity.
        if ($bus->schedules()->exists()) {
            return redirect()->route('admin.buses.index')
                ->with('error', 'Cannot delete bus. It has associated schedules.');
        }

        $bus->delete();

        return redirect()->route('admin.buses.index')
            ->with('success', 'Bus deleted successfully.');
    }
}