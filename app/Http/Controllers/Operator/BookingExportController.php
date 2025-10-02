<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;
use PDF;

class BookingExportController extends Controller
{
    private function getFilteredBookings(Request $request)
    {
        $query = Booking::whereHas('schedule.bus', function($q) {
                $q->where('operator_id', Auth::id());
            })
            ->with('user', 'schedule.bus', 'schedule.route')
            ->orderBy('created_at', 'desc');

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment_status') && $request->payment_status !== 'all') {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('schedule_id')) {
            $query->where('schedule_id', $request->schedule_id);
        }

        return $query->get();
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $bookings = $this->getFilteredBookings($request);

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="bookings.csv"',
        ];

        $callback = function() use ($bookings) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Booking ID', 'Passenger', 'Email', 'Phone',
                'Route', 'Bus', 'Seat', 'Fare',
                'Status', 'Payment Status', 'Booked On'
            ]);

            foreach ($bookings as $booking) {
                fputcsv($handle, [
                    $booking->id,
                    $booking->user->name,
                    $booking->user->email,
                    $booking->user->phone ?? '-',
                    $booking->schedule->route->origin.' -> '.$booking->schedule->route->destination,
                    $booking->schedule->bus->bus_number,
                    $booking->seat_number,
                    $booking->schedule->fare,
                    ucfirst($booking->status),
                    ucfirst($booking->payment_status),
                    $booking->created_at->format('Y-m-d H:i'),
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $bookings = $this->getFilteredBookings($request);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('operator.bookings.export_pdf', compact('bookings'));
        return $pdf->download('bookings.pdf');
    }
}
