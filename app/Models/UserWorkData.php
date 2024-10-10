<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWorkData extends Model
{
    use HasFactory;

    public function users()
    {
        //a work data info can only belong to one user
        return $this->belongsTo(User::class);
    }
}
