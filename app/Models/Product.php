<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
   
    
    protected $fillable = [
        'name', 'category_id', 'price', 'discount_code', 'description', 'stock', 'image','discount_amount','discounted_price'
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

    
}
