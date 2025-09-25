<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('buses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('operator_id')->constrained('users')->onDelete('cascade');
            $table->string('bus_number')->unique();
            $table->string('bus_type')->nullable();
            $table->integer('capacity');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('buses');
    }
};
