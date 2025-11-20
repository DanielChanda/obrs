<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Bus;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // Constants for cache keys and configuration
    private const CACHE_TTL = 3600; // 1 hour
    private const CACHE_KEY_PREFIX = 'reports_';
    private const DATE_FORMAT = 'F Y';

    public function index(Request $request)
    {
        // Check for cached data or generate fresh statistics
        $cacheKey = self::CACHE_KEY_PREFIX . 'dashboard';
        $data = Cache::remember($cacheKey, self::CACHE_TTL, function () {
            return $this->generateDashboardData();
        });

        // Apply filters if requested
        if ($request->hasAny(['year', 'month'])) {
            $data = $this->generateDashboardData($request);
        }

        return view('admin.reports.index', $data);
    }

    /**
     * Generate comprehensive dashboard data with optional filters
     */
    private function generateDashboardData(Request $request = null): array
    {
        $year = $request?->input('year');
        $month = $request?->input('month');

        // Base queries with optional date filtering
        $bookingQuery = Booking::query();
        $userQuery = User::query();

        if ($year) {
            $bookingQuery->whereYear('created_at', $year);
            if ($month) {
                $bookingQuery->whereMonth('created_at', $month);
            }
        }

        // Total counts with efficient single queries
        $totalOperators = (clone $userQuery)->where('role', 'operator')->count();
        $totalPassengers = (clone $userQuery)->where('role', 'passenger')->count();
        $totalBuses = Bus::count();
        $totalBookings = $bookingQuery->count();

        // Total revenue with optimized query
        $totalRevenue = $bookingQuery->clone()
            ->where('payment_status', 'paid')
            ->join('schedules', 'bookings.schedule_id', '=', 'schedules.id')
            ->sum('schedules.fare');

        // Monthly statistics with efficient grouping
        $monthlyStats = $this->getMonthlyStatistics($year);

        // Transform stats for Chart.js
        $chartData = $this->transformChartData($monthlyStats);
        

        return [
            'totalOperators' => $totalOperators,
            'totalPassengers' => $totalPassengers,
            'totalBuses' => $totalBuses,
            'totalBookings' => $totalBookings,
            'totalRevenue' => $totalRevenue,
            'monthlyStats' => $monthlyStats,
            'chartLabels' => $chartData['labels'],
            'chartBookingData' => $chartData['bookings'],
            'chartRevenueData' => $chartData['revenue'],
            'filterYear' => $year,
            'filterMonth' => $month,
            'availableYears' => $this->getAvailableYears(),
        ];
    }

    /**
     * Get monthly statistics with optimized query
     */
    private function getMonthlyStatistics($year = null)
{
    $connection = DB::connection();
    $driver = $connection->getDriverName();
    
    $query = DB::table('bookings')
        ->join('schedules', 'bookings.schedule_id', '=', 'schedules.id');
    
    if ($driver === 'sqlite') {
        $query->select(
            DB::raw('CAST(strftime("%Y", bookings.created_at) AS INTEGER) as year'),
            DB::raw('CAST(strftime("%m", bookings.created_at) AS INTEGER) as month'),
            DB::raw('COUNT(*) as total_bookings'),
            DB::raw('SUM(CASE WHEN bookings.payment_status = "paid" THEN schedules.fare ELSE 0 END) as revenue')
        )
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc');
    } else {
        $query->select(
            DB::raw('YEAR(bookings.created_at) as year'),
            DB::raw('MONTH(bookings.created_at) as month'),
            DB::raw('COUNT(*) as total_bookings'),
            DB::raw('SUM(CASE WHEN bookings.payment_status = "paid" THEN schedules.fare ELSE 0 END) as revenue')
        )
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc');
    }
    
    $query->when($year, fn($query) => $query->whereYear('bookings.created_at', $year));
    
    return $query->get();
}

    /**
     * Transform monthly stats for Chart.js
     */

    private function transformChartData($monthlyStats): array
    {
        $labels = [];
        $bookingData = [];
        $revenueData = [];

        foreach ($monthlyStats as $stat) {
            $date = Carbon::createFromDate($stat->year, $stat->month, 1);
            $labels[] = $date->format('F Y');
            $bookingData[] = $stat->total_bookings;
            $revenueData[] = floatval($stat->revenue);
        }

        return [
            'labels' => $labels,
            'bookings' => $bookingData,
            'revenue' => $revenueData,
        ];
    }

    /**
     * Get available years for filtering
     */
private function getAvailableYears(): array
{
    return Booking::selectRaw('CAST(strftime("%Y", created_at) AS INTEGER) as year')
        ->distinct()
        ->orderBy('year', 'desc')
        ->pluck('year')
        ->toArray();
}

    public function exportCsv(Request $request)
    {
        $fileName = 'bus_booking_reports_' . now()->format('Y_m_d') . '.csv';
        
        $bookings = $this->getExportData($request);

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($bookings) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8 compatibility
            fwrite($file, "\xEF\xBB\xBF");
            
            fputcsv($file, [
                'Booking ID', 
                'Passenger Name', 
                'Passenger Email',
                'Route', 
                'Bus Number', 
                'Seat Number', 
                'Fare', 
                'Booking Status', 
                'Payment Status', 
                'Booking Date',
                'Travel Date'
            ]);

            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->id,
                    $booking->user->name,
                    $booking->user->email,
                    $booking->schedule->route->origin . ' → ' . $booking->schedule->route->destination,
                    $booking->schedule->bus->bus_number,
                    $booking->seat_number,
                    number_format($booking->schedule->fare, 2),
                    ucfirst($booking->status),
                    ucfirst($booking->payment_status),
                    $booking->created_at->format('M j, Y g:i A'),
                    $booking->schedule->travel_date?->format('M j, Y') ?? 'N/A',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $bookings = $this->getExportData($request);
        $summary = [
            'total_bookings' => $bookings->count(),
            'total_revenue' => $bookings->where('payment_status', 'paid')->sum(fn($b) => $b->schedule->fare),
            'generated_at' => now()->format('F j, Y g:i A'),
        ];

        $pdf = Pdf::loadView('admin.reports.pdf', compact('bookings', 'summary'))
            ->setPaper('a4', 'landscape')
            ->setOption('enable_php', true)
            ->setOption('dpi', 150);

        $fileName = 'bus_booking_reports_' . now()->format('Y_m_d') . '.pdf';
        
        return $pdf->download($fileName);
    }

    /**
     * Get filtered data for exports
     */
    private function getExportData(Request $request)
    {
        return Booking::with([
                'user:id,name,email',
                'schedule.bus:id,bus_number',
                'schedule.route:id,origin,destination'
            ])
            ->when($request->filled('start_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '>=', $request->start_date);
            })
            ->when($request->filled('end_date'), function ($query) use ($request) {
                $query->whereDate('created_at', '<=', $request->end_date);
            })
            ->when($request->filled('payment_status'), function ($query) use ($request) {
                $query->where('payment_status', $request->payment_status);
            })
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Clear cached reports data
     */
    public function clearCache()
    {
        Cache::forget(self::CACHE_KEY_PREFIX . 'dashboard');
        
        return redirect()->route('admin.reports.index')
            ->with('success', 'Reports cache cleared successfully.');
    }
}
