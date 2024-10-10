<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FriendRequest extends Model
{
    use HasFactory;

    //data that should be hidden
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'is_accepted',
        'accepted_at',
    ];
    protected $hidden =[
        'token'
    ];

    const CREATED_AT = 'sent_at'; // Use sent_at instead of created_at, meaning that the sent_at will be handled the same way as a created_at should, sending a friend request would be sent at the current timestamp by default
    public function sender()
    {
        //a friend request can only be sent by one user
        return $this->belongsTo(User::class, 'sender_id');
    }
    public function receiver()
    {
        //a friend request has only one receiver, like that friend request cannot be returned by multiple users
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
