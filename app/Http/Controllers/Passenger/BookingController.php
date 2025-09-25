<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Route;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class BookingController extends Controller {
    public function dashboard() {
        $bookings = Booking::where('user_id', Auth::id())->with('schedule.bus', 'schedule.route')->get();
        return view('passenger.dashboard', compact('bookings'));
    }

    public function search(Request $request) {
        $request->validate([
            'origin' => 'required',
            'destination' => 'required',
            'date' => 'required|date',
        ]);

        $schedules = Schedule::with('bus', 'route')
            ->whereHas('route', function ($q) use ($request) {
                $q->where('origin', $request->origin)
                ->where('destination', $request->destination);
            })
            ->whereDate('departure_time', $request->date)
            ->where('available_seats', '>', 0)
            ->get();
        //dd( $schedules );
        return view('passenger.search_results', compact('schedules'));
    }

    public function book($scheduleId) {
        $schedule = Schedule::with('bus')->findOrFail($scheduleId);

        if ($schedule->available_seats <= 0) {
            return back()->withErrors(['seat' => 'No seats available']);
        }

        $booking = Booking::create([
            'user_id' => Auth::id(),
            'schedule_id' => $schedule->id,
            'seat_number' => rand(1, $schedule->bus->capacity),
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        $schedule->decrement('available_seats');

        // Redirect to payment page
        return redirect()->route('passenger.payment', $booking->id);
    }

    public function payment(Booking $booking) {
        return view('passenger.payment', compact('booking'));
    }

    public function processPayment(Request $request, Booking $booking) {
        // For now, simulate payment success
        $booking->update(['payment_status' => 'paid', 'status' => 'confirmed']);

        // Later: integrate Stripe/PayPal
        return redirect()->route('passenger.dashboard')->with('success', 'Payment successful! Ticket generated.');
    }

    public function showSearchForm() {
        $now = now();
        $routeIds = \App\Models\Schedule::where('available_seats', '>', 0)
            ->where('departure_time', '>', $now)
            ->pluck('route_id');
        $origins = Route::whereIn('id', $routeIds)->distinct()->pluck('origin');
        $destinations = Route::whereIn('id', $routeIds)->distinct()->pluck('destination');
        return view('passenger.search', compact('origins', 'destinations'));
    }

    public function ticket(Booking $booking) {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        $qrData = "Booking ID: {$booking->id}\n"
                . "Passenger: {$booking->user->name}\n"
                . "Bus: {$booking->schedule->bus->bus_number}\n"
                . "Route: {$booking->schedule->route->origin} → {$booking->schedule->route->destination}\n"
                . "Departure: {$booking->schedule->departure_time}\n"
                . "Seat: {$booking->seat_number}\n"
                . "Status: {$booking->status}";

        return view('passenger.ticket', compact('booking', 'qrData'));
    }

    public function downloadTicket(Booking $booking) {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access');
        }

        // QR code as base64 image
        $qrData = "Booking ID: {$booking->id}\n"
                . "Passenger: {$booking->user->name}\n"
                . "Bus: {$booking->schedule->bus->bus_number}\n"
                . "Route: {$booking->schedule->route->origin} → {$booking->schedule->route->destination}\n"
                . "Departure: {$booking->schedule->departure_time}\n"
                . "Seat: {$booking->seat_number}\n"
                . "Status: {$booking->status}";

        $qrImage = base64_encode(QrCode::encoding('UTF-8')->size(200)->generate($qrData));


        $pdf = Pdf::loadView('passenger.ticket_pdf', [
            'booking' => $booking,
            'qrImage' => $qrImage
        ]);

        return $pdf->download("ticket-{$booking->id}.pdf");
    }


}
