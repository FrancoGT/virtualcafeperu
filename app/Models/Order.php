<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    // Accesor para obtener el total del pedido
    public function getTotalAttribute()
    {
        return $this->orderDetails->sum(function ($detail) {
            return $detail->quantity * $detail->price;
        });
    }
}
