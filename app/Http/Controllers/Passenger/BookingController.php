<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Route;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Services\FlutterwaveService;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class BookingController extends Controller {
    protected $flutterwaveService;

    public function __construct(FlutterwaveService $flutterwaveService)
    {
        $this->flutterwaveService = $flutterwaveService;
    }
    
    public function dashboard() {
        $bookings = Booking::where('user_id', Auth::id())
            ->with('schedule.bus', 'schedule.route')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $stats = [
            'totalBookings' => Booking::where('user_id', Auth::id())->count(),
            'upcomingTrips' => Booking::where('user_id', Auth::id())
                ->whereHas('schedule', function($q) {
                    $q->where('departure_time', '>', now());
                })
                ->where('status', 'confirmed')
                ->count(),
            'completedTrips' => Booking::where('user_id', Auth::id())
                ->where('status', 'completed')
                ->count(),
            'totalSpent' => Booking::where('user_id', Auth::id())
                ->where('payment_status', 'paid')
                ->with('schedule')
                ->get()
                ->sum(function($booking) {
                    return $booking->schedule->fare;
                })
        ];
        
        return view('passenger.dashboard', array_merge(['bookings' => $bookings], $stats));
    }

    public function bookings() {
        $bookings = Booking::where('user_id', Auth::id())->with('schedule.bus', 'schedule.route')->get();
        return view('passenger.my_bookings', compact('bookings'));
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
        // Verify booking belongs to user and is unpaid
        if ($booking->user_id !== Auth::id() || $booking->payment_status === 'paid') {
            return redirect()->route('passenger.dashboard')->with('error', 'Invalid booking access.');
        }

        return view('passenger.payment', compact('booking'));
    }

    public function processPayment(Request $request, Booking $booking) {
        // For now, simulate payment success
        $booking->update(['payment_status' => 'paid', 'status' => 'confirmed']);

        // Later: integrate Stripe/PayPal
        return redirect()->route('passenger.dashboard')->with('success', 'Payment successful! Ticket generated.');
    }

        /**
     * Initialize Flutterwave payment
     */
    public function initializePayment(Request $request, Booking $booking) {
        // Verify booking belongs to user
        if ($booking->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Check if booking is already paid
        if ($booking->payment_status === 'paid') {
            return response()->json(['error' => 'Booking already paid'], 400);
        }

        $user = Auth::user();
        $schedule = $booking->schedule;

        $paymentData = [
            'transaction_reference' => $this->flutterwaveService->generateTransactionReference(),
            'amount' => $schedule->fare,
            'currency' => 'ZMW', // or your preferred currency
            'customer_email' => $user->email,
            'customer_name' => $user->name,
            'customer_phone' => $user->phone ?? '',
            'redirect_url' => route('passenger.payment.callback'),
            'title' => 'Bus Ticket Payment',
            'description' => "Bus ticket from {$schedule->route->origin} to {$schedule->route->destination}",
            'meta' => [
                'booking_id' => $booking->id,
                'user_id' => $user->id,
                'schedule_id' => $schedule->id,
            ],
        ];

        // Store transaction reference in booking
        $booking->update([
            'transaction_reference' => $paymentData['transaction_reference']
        ]);

        $response = $this->flutterwaveService->initializePayment($paymentData);

        if ($response['status'] === 'success') {
            return response()->json([
                'status' => 'success',
                'message' => 'Payment initialized successfully',
                'data' => [
                    'payment_url' => $response['data']['link'],
                    'transaction_reference' => $paymentData['transaction_reference'],
                ]
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Failed to initialize payment',
            'data' => $response
        ], 400);
    }

     /**
     * Handle Flutterwave webhook
     */
    public function handleWebhook(Request $request) {
        $signature = $request->header('verif-hash');
        $payload = $request->all();

        // Validate webhook signature (you might want to skip this in local development)
        if (config('flutterwave.secret_hash') && $signature !== config('flutterwave.secret_hash')) {
            \Log::warning('Invalid Flutterwave webhook signature');
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $event = $payload['event'] ?? '';
        $data = $payload['data'] ?? [];

        \Log::info("Flutterwave Webhook: {$event}", $payload);

        switch ($event) {
            case 'charge.completed':
                return $this->handleChargeCompleted($data);
            
            default:
                \Log::info("Unhandled webhook event: {$event}");
                return response()->json(['status' => 'ignored'], 200);
        }
    }

    /**
     * Handle payment callback from Flutterwave
     */
    public function paymentCallback(Request $request) {
        $status = $request->query('status');
        $transactionId = $request->query('transaction_id');
        $txRef = $request->query('tx_ref');

        // Find booking by transaction reference
        $booking = Booking::where('transaction_reference', $txRef)->first();

        if (!$booking) {
            return redirect()->route('passenger.dashboard')->with('error', 'Invalid transaction reference.');
        }

        if ($status === 'successful') {
            // Verify the transaction
            $verification = $this->flutterwaveService->verifyTransaction($transactionId);

            if ($verification['status'] === 'success' && 
                $verification['data']['status'] === 'successful' &&
                $verification['data']['tx_ref'] === $txRef) {
                
                // Payment successful - update booking
                $booking->update([
                    'payment_status' => 'paid',
                    'status' => 'confirmed',
                    'payment_method' => 'flutterwave',
                    'paid_at' => now(),
                    'transaction_id' => $transactionId
                ]);

                return redirect()->route('passenger.ticket', $booking->id)
                    ->with('success', 'Payment completed successfully! Your ticket has been generated.');
            }
        }
    }

    /**
     * Handle completed charge webhook
     */
    protected function handleChargeCompleted($data) {
        $txRef = $data['tx_ref'] ?? '';
        $transactionId = $data['id'] ?? '';
        $status = $data['status'] ?? '';

        $booking = Booking::where('transaction_reference', $txRef)->first();

        if (!$booking) {
            \Log::warning("Booking not found for transaction reference: {$txRef}");
            return response()->json(['error' => 'Booking not found'], 404);
        }

        if ($status === 'successful' && $booking->payment_status !== 'paid') {
            $booking->update([
                'payment_status' => 'paid',
                'status' => 'confirmed',
                'paid_at' => now(),
                'transaction_id' => $transactionId
            ]);

            \Log::info("Booking {$booking->id} payment confirmed via webhook");
        }

        return response()->json(['status' => 'success'], 200);
    }

    public function showSearchForm() {
        //get the current time
        $now = now();

        // Fetch route IDs with available seats and future departure times
        $routeIds = Schedule::where('available_seats', '>', 0)
            ->where('departure_time', '>', $now)
            ->pluck('route_id');

        // Fetch schedules with related route information
        $schedules = \App\Models\Schedule::with('route')
            ->select('id', 'route_id', 'departure_time')
            ->get();
        
        // Fetch all valid origin-destination pairs from the Route model.
        // This ensures only real, available trips are selectable.
        $trips = Route::whereIn('id', $routeIds)
            ->select('origin', 'destination')
            ->get();
        
        // get distinct origins and destinations for dropdowns
        $origins = Route::whereIn('id', $routeIds)->distinct()->pluck('origin');
        $destinations = Route::whereIn('id', $routeIds)->distinct()->pluck('destination');

        // Pass $schedules along with $trips, $origins, and $destinations
        return view('passenger.search', compact('origins', 'destinations', 'trips', 'schedules'));
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
