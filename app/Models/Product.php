<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
   
    
    protected $fillable = [
        'name', 'category_id', 'price', 'discount_code', 'description', 'stock', 'image','discount_amount','discounted_price','brand_name','rating','review','category_name'
    ];
    protected $table = 'products';
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function getAverageRatingAttribute()
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }
    protected $casts = [
        'images' => 'array',
    ];
    public function category()
    {
        return $this->belongsTo(ProductCat::class, 'category_id');
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = $value;
        $this->calculateDiscount();
    }

    public function setDiscountCodeAttribute($value)
    {
        $this->attributes['discount_code'] = $value;
        $this->calculateDiscount();
    }

    private function calculateDiscount()
    {
        $price = $this->attributes['price'] ?? 0;
        $discount = $this->attributes['discount_code'] ?? 0;

        $discountAmount = ($price * $discount) / 100;
        $this->attributes['discount_amount'] = round($discountAmount, 2);

        $this->attributes['discounted_price'] = round($price - $discountAmount, 2);
    }
}
