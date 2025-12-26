<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // disesuiakan dengan migration table orders
    protected $fillable = ['order_code', 'user_id', 'subtotal', 'tax', 'grand_total','status', 'table_number', 'payment_method', 'notes', 'created_at', 'updated_at'];
    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->belongsTo(User::class); // setiap order hanya memiliki 1 user
    }

    // relasi
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class); // setiap order bisa memiliki banyak order item
    }
}
