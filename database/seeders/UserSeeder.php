<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder {
    public function run(): void {
        // Admin
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@obrs.com',
            'role' => 'admin',
            'password' => bcrypt('admin123'),
        ]);

        // Operators
        User::factory(5)->create(['role' => 'operator']);

        // Passengers
        User::factory(10)->create(['role' => 'passenger']);
    }
}

