<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOperators = User::where('role', 'operator')->count();
        $totalPassengers = User::where('role', 'passenger')->count();
        $totalBookings = Booking::count();
        $totalUsers = User::where('role', '!=', 'admin')->count();
        $totalRevenue = Booking::where('payment_status', 'paid')
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.id')
            ->sum('schedules.fare');

        return view('admin.dashboard', compact(
            'totalOperators',
            'totalPassengers',
            'totalBookings',
            'totalRevenue',
            'totalUsers'
        ));
    }
}
