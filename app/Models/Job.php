<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_content',
        'publisher_id',
        'is_company',
        'company_pfp',
        'company_name'
    ];
    public function users()
    {
        //a job can only be listed by one user, similar to posts
        return $this->belongsTo(User::class);
    }
    public function job_applies()
    {
        //a job can have many applications
        return $this->hasMany(JobApply::class);
    }
}
