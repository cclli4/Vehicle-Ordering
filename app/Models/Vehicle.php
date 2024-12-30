<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'vehicle_number',
        'type', // passenger/cargo
        'brand',
        'model',
        'capacity',
        'status', // available/in_use/maintenance
        'ownership', // company/rental
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
