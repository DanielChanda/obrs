<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_id')->constrained('buses')->onDelete('cascade');
            $table->foreignId('route_id')->constrained('routes')->onDelete('restrict');
            $table->dateTime('departure_time');
            $table->dateTime('arrival_time');
            $table->decimal('fare', 10, 2);
            $table->integer('available_seats');
            $table->enum('status', ['scheduled', 'completed', 'cancelled'])->default('scheduled');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('schedules');
    }
};
