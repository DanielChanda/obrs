<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Bus;
use App\Models\Schedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $operatorId = Auth::id();

        $totalBuses = Bus::where('operator_id', $operatorId)->count();
        $totalSchedules = Schedule::whereHas('bus', fn($q) => $q->where('operator_id', $operatorId))->count();
        $totalBookings = Booking::whereHas('schedule.bus', fn($q) => $q->where('operator_id', $operatorId))->count();

        // ✅ Fix revenue calculation by joining schedules
        $revenue = Booking::whereHas('schedule.bus', fn($q) => $q->where('operator_id', $operatorId))
            ->where('payment_status', 'paid')
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.id')
            ->sum('schedules.fare');

        // ✅ Fix monthly stats with join - SQLite compatible
        $connection = DB::connection();
        $driver = $connection->getDriverName();
        
        if ($driver === 'sqlite') {
            $monthlyStats = Booking::whereHas('schedule.bus', fn($q) => $q->where('operator_id', $operatorId))
                ->join('schedules', 'bookings.schedule_id', '=', 'schedules.id')
                ->selectRaw('CAST(strftime("%m", bookings.created_at) AS INTEGER) as month, COUNT(*) as total, SUM(CASE WHEN bookings.payment_status="paid" THEN schedules.fare ELSE 0 END) as revenue')
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        } else {
            $monthlyStats = Booking::whereHas('schedule.bus', fn($q) => $q->where('operator_id', $operatorId))
                ->join('schedules', 'bookings.schedule_id', '=', 'schedules.id')
                ->selectRaw('MONTH(bookings.created_at) as month, COUNT(*) as total, SUM(CASE WHEN bookings.payment_status="paid" THEN schedules.fare ELSE 0 END) as revenue')
                ->groupBy('month')
                ->orderBy('month')
                ->get();
        }

        // Prepare chart data
        $months = [];
        $bookingsPerMonth = [];
        $revenuePerMonth = [];

        foreach ($monthlyStats as $stat) {
            $months[] = \Carbon\Carbon::create()->month($stat->month)->format('F');
            $bookingsPerMonth[] = $stat->total;
            $revenuePerMonth[] = $stat->revenue;
        }

        return view('operator.reports.index', compact(
            'totalBuses',
            'totalSchedules',
            'totalBookings',
            'revenue',
            'monthlyStats',
            'months',
            'bookingsPerMonth',
            'revenuePerMonth'
        ));
    }
}