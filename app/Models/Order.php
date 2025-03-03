<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'name', 'email', 'address', 'city', 'zip', 
        'total', 'discount_code', 'status','brand_name', 'product_image', 'mobile','payment_mode'
    ];
   
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'processing']);
    }
}
