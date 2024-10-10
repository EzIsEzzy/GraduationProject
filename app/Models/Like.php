<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'is_like',
    ];

    public function users()
    {
        //a like can only belongs to one user, that like cannot be from multiple users
        return $this->belongsTo(User::class);
    }

    public function posts()
    {
        //a like can only belongs to one post, that like itseld cannot exist in multiple posts
        return $this->belongsTo(Post::class);
    }
}
