<?php

namespace App\Models;

use App\Models\Jobs\Job;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['user_id', 'name', 'description', 'is_archived'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class)
            ->orderBy('sort_order_for_project')
            ->orderBy('name');
    }
}
