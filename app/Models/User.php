<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'picture',
        'gender',
        'birthdate'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    //relations
    public function posts()
    {
        //a user can have many posts appended to him
        return $this->hasMany(Post::class);
    }
    public function likes()
    {
        //a user can like as many posts and comments as he can, it can be appended to him
        return $this->hasMany(Like::class);
    }
    public function comments()
    {
        //a user can have many comments, same post or not
        return $this->hasMany(Comment::class);
    }
    public function jobs()
    {
        //a user can publish many jobs
        return $this->hasMany(Job::class);
    }
    public function job_applies()
    {
        //a user can apply to many jobs (per requirement)
        return $this->hasMany(JobApply::class);
    }
    public function friend_requests()
    {
        //a user can send many friend requests
        return $this->hasMany(FriendRequest::class);
    }
    public function user_work_data()
    {
        //a user can only have one work data (like a work info in the profile)
        return $this->hasOne(UserWorkData::class);
    }
    public function comment_likes()
    {
        //a user can like many comments
        return $this->hasMany(CommentLike::class);
    }
}
