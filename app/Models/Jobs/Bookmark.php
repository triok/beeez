<?php

namespace App\Models\Jobs;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    protected $guarded = ['id'];

    function user(){
        return $this->belongsTo(\App\User::class,'user_id','id');
    }

    function job(){
        return $this->belongsTo(\App\Models\Jobs\Job::class,'job_id','id');
    }
}
