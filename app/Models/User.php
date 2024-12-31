<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'approval_level'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'approval_level' => 'integer',
    ];
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function approvals()
    {
        return $this->hasMany(BookingApproval::class, 'approver_id');
    }

    // Helper methods buat cek role
    public function hasRole($role)
    {
        return $this->role === $role;
    }
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isApprover()
    {
        return $this->role === 'approver';
    }

    public function isDriver()
    {
        return $this->role === 'driver';
    }
}