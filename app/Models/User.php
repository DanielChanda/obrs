<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email', 
        'password', 
        'phone', 
        'role',
        'address'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function buses() {
        return $this->hasMany(Bus::class, 'operator_id');
    }

    public function routes()
    {
        return $this->hasMany(Route::class, 'operator_id');
    }

    public function bookings() {
        return $this->hasMany(Booking::class);
    }

    public function isPassenger() { return $this->role === 'passenger'; }
    public function isOperator() { return $this->role === 'operator'; }
    public function isAdmin() { return $this->role === 'admin'; }

    public function getNameAttribute(){
        return $this->first_name . ' ' . $this->last_name;
    }
}
