<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    //decide the fillables and then the realtions
    protected $fillable = [
        'post_id',
        'commenter_id',
        'comment_line',
        'is_comment'
    ];

    //decide the relations to each table

    public function users()
    {
        //each comment can only belongs to one user
        return $this->belongsTo(User::class);
    }
    public function likes()
    {
        //each comment can have many likes
        return $this->hasMany(Like::class);
    }
    public function posts()
    {
        //each comment can only be in one post, that same comment cannot exist in many posts
        return $this->belongsTo(Post::class);
    }
    public function jobs()
    {
        //each comment can only be in one job, that same comment cannot exist in many jobs
        return $this->belongsTo(Job::class);
    }


}
