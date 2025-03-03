<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id' , 'product_id' , 'rating' , 'review','title'
    ];
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }
   
    public function user()
    {
        return $this->belongsTo(User::class,);
    }
    
}
