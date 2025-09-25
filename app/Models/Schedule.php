<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model {
    use HasFactory;

    protected $fillable = [
        'bus_id', 'route_id', 'departure_time', 'arrival_time',
        'fare', 'available_seats', 'status'
    ];

    public function bus() {
        return $this->belongsTo(Bus::class);
    }

    public function route() {
        return $this->belongsTo(Route::class);
    }

    public function bookings() {
        return $this->hasMany(Booking::class);
    }
}
