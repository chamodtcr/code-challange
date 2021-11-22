<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title','content','user_id','publish_status'];

    public function image()
    {
        return $this->hasMany(Image::class,'post_id');
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }
}
