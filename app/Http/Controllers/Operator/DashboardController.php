<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Schedule;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $operator = Auth::user();
        
        $stats = [
            'totalBuses' => Bus::where('operator_id', $operator->id)->count(),
            'activeSchedules' => Schedule::whereHas('bus', function($query) use ($operator) {
                $query->where('operator_id', $operator->id);
            })->where('status', 'scheduled')->count(),
            'totalBookings' => Booking::whereHas('schedule.bus', function($query) use ($operator) {
                $query->where('operator_id', $operator->id);
            })->count(),
            'todayBookings' => Booking::whereHas('schedule.bus', function($query) use ($operator) {
                $query->where('operator_id', $operator->id);
            })->whereDate('created_at', today())->count(),
        ];

        // Recent schedules
        $recentSchedules = Schedule::whereHas('bus', function($query) use ($operator) {
                $query->where('operator_id', $operator->id);
            })
            ->with('bus', 'route')
            ->orderBy('departure_time', 'desc')
            ->take(5)
            ->get();

        // Recent bookings
        $recentBookings = Booking::whereHas('schedule.bus', function($query) use ($operator) {
                $query->where('operator_id', $operator->id);
            })
            ->with('user', 'schedule.bus', 'schedule.route')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('operator.dashboard.index', compact('stats', 'recentSchedules', 'recentBookings', 'operator'));
    }
}