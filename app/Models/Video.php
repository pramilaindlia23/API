<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'category_id', 'file_path', 'mime_type', 'file_size'];

    public function category()
    {
        return $this->belongsTo(VideoCat::class);
    }
}
