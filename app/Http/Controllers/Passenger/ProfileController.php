<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $recentBookings = $user->bookings()
            ->with('schedule.bus', 'schedule.route')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        return view('passenger.profile.show', compact('user', 'recentBookings'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('passenger.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ]);

        $user->update($request->only('first_name', 'last_name', 'email', 'phone', 'address'));

        return redirect()->route('passenger.profile.show')
            ->with('success', 'Profile updated successfully!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('passenger.profile.show')
            ->with('success', 'Password changed successfully!');
    }

    public function bookingHistory(Request $request)
    {
        $user = Auth::user();
        $statusFilter = $request->get('status', 'all');
        
        // Start with base query
        $query = $user->bookings()
            ->with('schedule.bus', 'schedule.route', 'payment')
            ->orderBy('created_at', 'desc');
        
        // Apply filters
        switch ($statusFilter) {
            case 'upcoming':
                $query->whereHas('schedule', function($q) {
                    $q->where('departure_time', '>', now());
                })->where('status', 'confirmed');
                break;
            case 'completed':
                $query->where('status', 'completed');
                break;
            case 'cancelled':
                $query->where('status', 'cancelled');
                break;
            case 'pending':
                $query->where('status', 'pending');
                break;
            // 'all' shows everything
        }
        
        $bookings = $query->paginate(10);
        
        // Pass filter state to view
        $filters = [
            'status' => $statusFilter,
            'statusOptions' => [
                'all' => 'All Bookings',
                'upcoming' => 'Upcoming Trips',
                'completed' => 'Completed Trips',
                'cancelled' => 'Cancelled',
                'pending' => 'Pending'
            ]
        ];
        
        return view('passenger.profile.booking-history', compact('bookings', 'filters'));
    }

    //method for booking details
    public function bookingDetails($id)
    {
        $booking = Auth::user()->bookings()
            ->with('schedule.bus', 'schedule.route', 'payment')
            ->findOrFail($id);
        
        return view('passenger.profile.booking-details', compact('booking'));
    }

    public function cancelBooking($id)
    {
        $booking = Auth::user()->bookings()->findOrFail($id);
        
        // Check if booking can be cancelled
        if ($booking->status !== 'confirmed' || $booking->schedule->departure_time <= now()) {
            return redirect()->back()->withErrors(['error' => 'This booking cannot be cancelled.']);
        }
        
        // Update booking status and increment available seats
        $booking->update(['status' => 'cancelled']);
        $booking->schedule->increment('available_seats');
        
        // TODO: Handle refund logic here if payment was made
        
        return redirect()->route('passenger.profile.booking-history')
            ->with('success', 'Booking cancelled successfully.');
    }
}