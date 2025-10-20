<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\Booking;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
        view()->composer('admin.layouts.app', function($view){
            $totalUsers = User::where('role', '!=', 'admin')->count();
            $totalBookings = Booking::count();
            $view->with(['totalUsers' => $totalUsers, 'totalBookings' => $totalBookings]);
        });
    }
}
