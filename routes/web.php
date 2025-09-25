<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Passenger\BookingController;
use App\Http\Controllers\Operator\BusController;
use App\Http\Controllers\Operator\ScheduleController;
use App\Http\Controllers\Admin\AdminController;

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
    Route::get('/search', [BookingController::class, 'showSearchForm'])->name('passenger.search.form');
    Route::post('/search', [BookingController::class, 'search'])->name('passenger.search');
    Route::post('/book/{schedule}', [BookingController::class, 'book'])->name('passenger.book');
    Route::get('/ticket/{booking}', [BookingController::class, 'ticket'])->name('passenger.ticket');
    Route::get('/ticket/{booking}/download', [BookingController::class, 'downloadTicket'])->name('passenger.ticket.download');
});

// Operator
Route::prefix('operator')->middleware(['auth'])->group(function() {
    Route::get('/dashboard', [BusController::class, 'index'])->name('operator.dashboard');
    Route::resource('buses', BusController::class);
    Route::resource('schedules', ScheduleController::class);
});

// Admin
Route::prefix('admin')->middleware(['auth'])->group(function() {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

//payment
Route::get('/payment/{booking}', [BookingController::class, 'payment'])->name('passenger.payment');
Route::post('/payment/{booking}', [BookingController::class, 'processPayment'])->name('passenger.processPayment');
