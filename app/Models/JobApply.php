<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApply extends Model
{
    use HasFactory;

    protected $fillable = [
        'applied_job',
        'candidate_id',
        'is_accepted',
        'uploaded_cv'
    ];
    public function users()
    {
        //a job application by itself only represents one user, so it belongs to it
        return $this->belongsTo(User::class);
    }
}
