<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoCat extends Model
{
    use HasFactory;
    protected $fillable = ['category_name'];
    protected $table = 'videocategory';

    public function videos()
    {
        return $this->hasMany(Video::class);
    }

}
