<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bus extends Model {
    use HasFactory;

    protected $fillable = ['operator_id', 'bus_number', 'bus_type', 'capacity', 'status'];

    public function operator() {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function schedules() {
        return $this->hasMany(Schedule::class);
    }
}
