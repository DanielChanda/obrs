<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Passenger\BookingController;
use App\Http\Controllers\Passenger\ProfileController;
use App\Http\Controllers\Operator\BusController;
use App\Http\Controllers\Operator\DashboardController;
use App\Http\Controllers\Operator\BookingController as OperatorBookingController;
use App\Http\Controllers\Operator\RouteController;
use App\Http\Controllers\Operator\ScheduleController;
use App\Http\Controllers\Operator\BookingExportController;
use App\Http\Controllers\Operator\ProfileController as OperatorProfileController;
use App\Http\Controllers\Operator\ReportController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;


Route::get('/', fn() => view('welcome'));

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Passenger
Route::middleware(['auth'])->group(function() {
    Route::get('/dashboard', [BookingController::class, 'dashboard'])->name('passenger.dashboard');
    Route::get('/bookings', [BookingController::class, 'bookings'])->name('passenger.bookings');
    Route::get('/search', [BookingController::class, 'showSearchForm'])->name('passenger.search.form');
    Route::post('/search', [BookingController::class, 'search'])->name('passenger.search');
    Route::post('/book/{schedule}', [BookingController::class, 'book'])->name('passenger.book');
    Route::get('/ticket/{booking}', [BookingController::class, 'ticket'])->name('passenger.ticket');
    Route::get('/ticket/{booking}/download', [BookingController::class, 'downloadTicket'])->name('passenger.ticket.download');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('passenger.profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('passenger.profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('passenger.profile.update');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('passenger.profile.change-password');
    Route::get('/profile/booking-history', [ProfileController::class, 'bookingHistory'])->name('passenger.profile.booking-history');

    // Booking details and management
    Route::get('/profile/booking/{id}', [ProfileController::class, 'bookingDetails'])
        ->name('passenger.profile.booking-details');
    Route::delete('/bookings/{booking}/cancel', [ProfileController::class, 'cancelBooking'])
        ->name('passenger.bookings.cancel');
});

// Operator
// Operator Routes
Route::prefix('operator')->name('operator.')->middleware(['auth', 'operator'])->group(function() {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Buses
    Route::resource('buses', BusController::class);
    
    // Routes
    Route::resource('routes', RouteController::class);
    
    // Schedules
    Route::resource('schedules', ScheduleController::class);
    Route::patch('schedules/{schedule}/cancel', [ScheduleController::class, 'cancel'])->name('schedules.cancel');
    
    // Bookings
    Route::resource('bookings', OperatorBookingController::class);
    Route::delete('/{booking}/cancel', [ProfileController::class, 'cancelBooking'])
        ->name('bookings.cancel');
    
    // Export routes
    Route::get('/bookings/export/csv', [BookingExportController::class, 'exportCsv'])->name('bookings.export.csv');
    Route::get('/bookings/export/pdf', [BookingExportController::class, 'exportPdf'])->name('bookings.export.pdf');

    // Profile
    Route::get('/profile', [OperatorProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [OperatorProfileController::class, 'update'])->name('profile.update');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});

// Admin


Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // User Management
        Route::resource('users', UserController::class);

        // Reports
        Route::get('reports', [AdminReportController::class, 'index'])->name('reports.index');
        Route::get('reports/export/csv', [AdminReportController::class, 'exportCsv'])->name('reports.export.csv');
        Route::get('reports/export/pdf', [AdminReportController::class, 'exportPdf'])->name('reports.export.pdf');
        Route::post('reports/clear-cache', [AdminReportController::class, 'clearCache'])
            ->name('reports.clear-cache');
    });


//payment
Route::get('/payment/{booking}', [BookingController::class, 'payment'])->name('passenger.payment');
Route::post('/payment/{booking}', [BookingController::class, 'processPayment'])->name('passenger.processPayment');
