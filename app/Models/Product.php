<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'price',
        'description',
        'image',
        'stock',
       'discount_amount', 
       'discounted_price',
       'discount_code',
       'discount_percentage',
       'category_id'
    ];
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function getAverageRatingAttribute()
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
