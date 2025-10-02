<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::whereHas('schedule.bus', function($q) {
                $q->where('operator_id', Auth::id());
            })
            ->with('user', 'schedule.bus', 'schedule.route')
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status') && $request->payment_status !== 'all') {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by schedule
        if ($request->filled('schedule_id')) {
            $query->where('schedule_id', $request->schedule_id);
        }

        $bookings = $query->paginate(15);
        $filters = $request->only(['status', 'payment_status', 'date_from', 'date_to', 'schedule_id']);

        // Get schedules for filter dropdown
        $schedules = Schedule::whereHas('bus', function($q) {
                $q->where('operator_id', Auth::id());
            })
            ->with('route')
            ->orderBy('departure_time', 'desc')
            ->get();

        return view('operator.bookings.index', compact('bookings', 'filters', 'schedules'));
    }

    public function show(Booking $booking)
    {
        // Verify the booking belongs to the current operator
        $this->authorizeOperatorBooking($booking);

        $booking->load('user', 'schedule.bus', 'schedule.route', 'payment');

        return view('operator.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $this->authorizeOperatorBooking($booking);

        // Only allow editing if schedule hasn't departed yet
        if ($booking->schedule->departure_time <= now()) {
            return redirect()->route('operator.bookings.show', $booking->id)
                ->withErrors(['error' => 'Cannot edit booking for a schedule that has already departed.']);
        }

        return view('operator.bookings.edit', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        $this->authorizeOperatorBooking($booking);

        // Prevent editing if schedule has departed
        if ($booking->schedule->departure_time <= now()) {
            return redirect()->route('operator.bookings.show', $booking->id)
                ->withErrors(['error' => 'Cannot update booking for a schedule that has already departed.']);
        }

        $request->validate([
            'seat_number' => 'required|integer|min:1|max:' . $booking->schedule->bus->capacity,
            'status' => 'required|in:pending,confirmed,cancelled,completed',
            'payment_status' => 'required|in:unpaid,paid,refunded',
        ]);

        // Check if seat is available (excluding current booking)
        if ($request->seat_number != $booking->seat_number) {
            $seatTaken = Booking::where('schedule_id', $booking->schedule_id)
                ->where('seat_number', $request->seat_number)
                ->where('id', '!=', $booking->id)
                ->where('status', '!=', 'cancelled')
                ->exists();

            if ($seatTaken) {
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['seat_number' => 'This seat is already taken for this schedule.']);
            }
        }

        $oldStatus = $booking->status;
        $oldPaymentStatus = $booking->payment_status;

        $booking->update($request->only('seat_number', 'status', 'payment_status'));

        // Handle seat availability updates
        if ($oldStatus === 'confirmed' && $request->status === 'cancelled') {
            // Free up the seat when cancelling a confirmed booking
            $booking->schedule->increment('available_seats');
        } elseif ($oldStatus === 'cancelled' && $request->status === 'confirmed') {
            // Reserve the seat when confirming a cancelled booking
            if ($booking->schedule->available_seats > 0) {
                $booking->schedule->decrement('available_seats');
            }
        }

        // Handle payment status changes
        if ($oldPaymentStatus === 'unpaid' && $request->payment_status === 'paid') {
            // Record payment if needed
            // This is where you'd integrate with payment gateway
        }

        return redirect()->route('operator.bookings.show', $booking->id)
            ->with('success', 'Booking updated successfully!');
    }

    public function confirm(Booking $booking)
    {
        $this->authorizeOperatorBooking($booking);

        if ($booking->schedule->departure_time <= now()) {
            return redirect()->back()
                ->withErrors(['error' => 'Cannot confirm booking for a schedule that has already departed.']);
        }

        if ($booking->status === 'confirmed') {
            return redirect()->back()->with('info', 'Booking is already confirmed.');
        }

        // Check seat availability
        if ($booking->schedule->available_seats <= 0) {
            return redirect()->back()
                ->withErrors(['error' => 'No seats available for confirmation.']);
        }

        $booking->update([
            'status' => 'confirmed',
            'payment_status' => 'paid', // Auto-mark as paid when operator confirms
        ]);

        // Update available seats
        $booking->schedule->decrement('available_seats');

        return redirect()->back()->with('success', 'Booking confirmed successfully!');
    }

    public function cancel(Booking $booking)
    {
        $this->authorizeOperatorBooking($booking);

        if ($booking->status === 'cancelled') {
            return redirect()->back()->with('info', 'Booking is already cancelled.');
        }

        $booking->update([
            'status' => 'cancelled',
            'payment_status' => $booking->payment_status === 'paid' ? 'refunded' : 'unpaid',
        ]);

        // Free up the seat if it was confirmed
        if ($booking->status === 'confirmed') {
            $booking->schedule->increment('available_seats');
        }

        return redirect()->back()->with('success', 'Booking cancelled successfully!');
    }

    public function checkIn(Booking $booking)
    {
        $this->authorizeOperatorBooking($booking);

        if ($booking->schedule->departure_time > now()) {
            return redirect()->back()
                ->withErrors(['error' => 'Cannot check in before schedule departure time.']);
        }

        if ($booking->status === 'completed') {
            return redirect()->back()->with('info', 'Passenger is already checked in.');
        }

        if ($booking->status !== 'confirmed') {
            return redirect()->back()
                ->withErrors(['error' => 'Only confirmed bookings can be checked in.']);
        }

        $booking->update(['status' => 'completed']);

        return redirect()->back()->with('success', 'Passenger checked in successfully!');
    }

    private function authorizeOperatorBooking(Booking $booking)
    {
        if ($booking->schedule->bus->operator_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this booking.');
        }
    }

    public function create(Request $request)
    {
        $schedules = Schedule::whereHas('bus', function($q) {
                $q->where('operator_id', Auth::id());
            })
            ->where('departure_time', '>', now())
            ->where('status', 'scheduled')
            ->with('bus', 'route')
            ->orderBy('departure_time', 'asc')
            ->get();

        $selectedSchedule = $request->get('schedule_id');
        $schedule = null;
        $occupiedSeats = [];
        $availableSeats = [];

        if ($selectedSchedule) {
            $schedule = Schedule::whereHas('bus', function($q) {
                    $q->where('operator_id', Auth::id());
                })
                ->with('bus', 'route', 'bookings')
                ->findOrFail($selectedSchedule);

            // Get occupied seats
            $occupiedSeats = $schedule->bookings()
                ->where('status', '!=', 'cancelled')
                ->pluck('seat_number')
                ->toArray();

            // Generate available seats
            $availableSeats = range(1, $schedule->bus->capacity);
            $availableSeats = array_diff($availableSeats, $occupiedSeats);
        }

        return view('operator.bookings.create', compact(
            'schedules', 
            'selectedSchedule', 
            'schedule',
            'occupiedSeats',
            'availableSeats'
        ));
    }

    public function storeManual(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'passenger_name' => 'required|string|max:255',
            'passenger_email' => 'required|email',
            'passenger_phone' => 'required|string|max:20',
            'seat_number' => 'required|integer|min:1',
            'fare' => 'required|numeric|min:1|max:1000',
            'payment_status' => 'required|in:unpaid,paid',
        ]);

        // Verify schedule belongs to operator
        $schedule = Schedule::whereHas('bus', function($q) {
                $q->where('operator_id', Auth::id());
            })
            ->findOrFail($request->schedule_id);

        // Check seat availability
        $seatTaken = Booking::where('schedule_id', $request->schedule_id)
            ->where('seat_number', $request->seat_number)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($seatTaken) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['seat_number' => 'This seat is already taken.']);
        }

        // Check if passenger exists, otherwise create
        $user = User::firstOrCreate(
            ['email' => $request->passenger_email],
            [
                'name' => $request->passenger_name,
                'phone' => $request->passenger_phone,
                'password' => bcrypt(Str::random(10)), // Random password
                'role' => 'passenger',
            ]
        );

        // Create booking
        $booking = Booking::create([
            'user_id' => $user->id,
            'schedule_id' => $request->schedule_id,
            'seat_number' => $request->seat_number,
            'status' => 'confirmed', // Auto-confirm manual bookings
            'payment_status' => $request->payment_status,
        ]);

        // Update available seats if paid
        if ($request->payment_status === 'paid') {
            $schedule->decrement('available_seats');
        }

        return redirect()->route('operator.bookings.show', $booking->id)
            ->with('success', 'Manual booking created successfully!');
    }

    public function seatMap($scheduleId)
    {
        $schedule = Schedule::whereHas('bus', function($q) {
                $q->where('operator_id', Auth::id());
            })
            ->with('bus', 'bookings')
            ->findOrFail($scheduleId);

        $occupiedSeats = $schedule->bookings()
            ->where('status', '!=', 'cancelled')
            ->pluck('seat_number')
            ->toArray();

        $seats = [];
        for ($i = 1; $i <= $schedule->bus->capacity; $i++) {
            $seats[] = [
                'number' => $i,
                'occupied' => in_array($i, $occupiedSeats),
                'booking' => $schedule->bookings->where('seat_number', $i)->first(),
            ];
        }

        return response()->json([
            'seats' => $seats,
            'capacity' => $schedule->bus->capacity,
            'available_seats' => $schedule->available_seats,
        ]);
    }
}