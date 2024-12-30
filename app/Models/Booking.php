<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'booking_number',
        'user_id',
        'vehicle_id',
        'driver_id',
        'purpose',
        'start_date',
        'end_date',
        'status', // pending/approved/rejected/completed
        'current_approval_level',
        'notes'
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($booking) {
            $booking->booking_number = 'BK' . date('Ymd') . str_pad(static::count() + 1, 4, '0', STR_PAD_LEFT);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function approvals()
    {
        return $this->hasMany(BookingApproval::class);
    }
}

