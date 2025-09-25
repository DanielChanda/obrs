<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model {
    use HasFactory;

    protected $fillable = ['booking_id', 'amount', 'method', 'status', 'transaction_id'];

    public function booking() {
        return $this->belongsTo(Booking::class);
    }
}
