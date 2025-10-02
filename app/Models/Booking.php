<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model {
    use HasFactory;

    protected $fillable = [
        'user_id', 'schedule_id', 'seat_number', 'status', 'payment_status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function schedule() {
        return $this->belongsTo(Schedule::class);
    }

    public function payment() {
        return $this->hasOne(Payment::class);
    }
}
