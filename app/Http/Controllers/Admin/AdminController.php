<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Booking;
use App\Models\Payment;

class AdminController extends Controller {
    public function dashboard() {
        $users = User::count();
        $bookings = Booking::count();
        $revenue = Payment::where('status', 'successful')->sum('amount');

        return view('admin.dashboard', compact('users', 'bookings', 'revenue'));
    }
}
