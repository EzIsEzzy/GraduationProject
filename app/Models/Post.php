<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'image',
        'publisher_id',
    ];

    public function users()
    {
        //a post can only belongs to one user, that same post cannot be from multiple users (it is not a collab reel in instagram)
        return $this->belongsTo(User::class);
    }
    public function likes()
    {
        //a post can have many likes
        return $this->hasMany(Like::class);
    }
    public function comments()
    {
        //a post can have many comments
        return $this->hasMany(Comment::class);
    }
}
