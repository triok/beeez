<?php

namespace App\Models\Jobs;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    protected $guarded = ['id'];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
