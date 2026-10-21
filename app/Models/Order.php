<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_id',
        'subtotal',
        'tax_amount',
        'delivery_fee',
        'total_amount',
        'status',
        'payment_status',
        'payment_method',
        'delivery_address',
        'contact_phone',
        'customer_notes',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class);
    }
}
