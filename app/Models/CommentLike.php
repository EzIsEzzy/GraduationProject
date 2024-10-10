<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment_id',
        'user_id',
        'is_liked'
    ];
    public function comments()
    {
        //the comment likes can only be appended to one comment, that one like comment cannot be linked to other comments
        return $this->belongsTo(Comment::class);
    }

    public function users()
    {
        //the comment likes can only be appended to one user, that one user cannot be linked to other likes
        return $this->belongsTo(User::class);
    }
}
