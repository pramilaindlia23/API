<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;
    protected $fillable = [
        'add_category', 'file_path', 'mime_type', 'file_size','category_name','category_id'
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
