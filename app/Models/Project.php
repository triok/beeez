<?php

namespace App\Models;

use App\Models\Jobs\Job;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['user_id', 'team_id', 'name', 'description', 'icon', 'is_archived'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class)
            ->orderBy('sort_order_for_project')
            ->orderBy('name');
    }
}
