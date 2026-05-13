<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'first_name', 'last_name', 'email', 'phone_number',
        'shipping_address', 'payment_method', 'is_paid', 'total_amount', 'status',
    ];

    protected $casts = ['is_paid' => 'boolean', 'total_amount' => 'decimal:2'];

    public function user()  { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(OrderItem::class); }
}
