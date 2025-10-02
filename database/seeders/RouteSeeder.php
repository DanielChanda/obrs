<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Route;
use App\Models\User;

class RouteSeeder extends Seeder {
    public function run(): void {
        // Get all operators
        $operators = User::where('role', 'operator')->get();
        
        if ($operators->count() > 0) {
            // Create routes for each operator
            foreach ($operators as $operator) {
                Route::factory(3)->create(['operator_id' => $operator->id]);
            }
        } else {
            // Fallback: create some routes with operator factory
            Route::factory(10)->create();
        }
        
        // Create specific popular Zambian routes for the first operator
        $firstOperator = User::where('role', 'operator')->first();
        if ($firstOperator) {
            $popularRoutes = [
                ['origin' => 'Lusaka', 'destination' => 'Livingstone', 'distance' => 470],
                ['origin' => 'Lusaka', 'destination' => 'Kitwe', 'distance' => 320],
                ['origin' => 'Lusaka', 'destination' => 'Ndola', 'distance' => 350],
                ['origin' => 'Kitwe', 'destination' => 'Livingstone', 'distance' => 450],
                ['origin' => 'Lusaka', 'destination' => 'Chipata', 'distance' => 570],
            ];
            
            foreach ($popularRoutes as $routeData) {
                Route::create(array_merge($routeData, ['operator_id' => $firstOperator->id]));
            }
        }
    }
}