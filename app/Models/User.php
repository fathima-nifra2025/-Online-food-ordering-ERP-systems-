<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isRestaurantStaff(): bool
    {
        return $this->role === 'restaurant_staff';
    }

    public function isDeliveryStaff(): bool
    {
        return $this->role === 'delivery_staff';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class, 'delivery_staff_id');
    }
}
