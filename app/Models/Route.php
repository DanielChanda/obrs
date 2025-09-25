<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Route extends Model {
    use HasFactory;

    protected $fillable = ['origin', 'destination', 'distance'];

    public function schedules() {
        return $this->hasMany(Schedule::class);
    }
}
