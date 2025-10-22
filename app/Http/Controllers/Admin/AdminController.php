<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use App\Models\Bus;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalOperators = User::where('role', 'operator')->count();
        $totalBookings = Booking::count();
        $totalRevenue = Booking::where('payment_status', 'paid')
            ->with('schedule')
            ->get()
            ->sum(fn($b) => $b->schedule->fare);

        return view('admin.dashboard', compact(
            'totalUsers', 'totalOperators', 'totalBookings', 'totalRevenue'
        ));
    }
}

