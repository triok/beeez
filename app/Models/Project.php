<?php

namespace App\Models;

use App\Models\Jobs\Job;
use App\Models\Traits\Favoritable;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use Favoritable;

    protected $guarded = ['id'];

    protected $dates = ['deadline_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'project_users', 'project_id', 'user_id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function structure()
    {
        return $this->belongsTo(Structure::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class)
            ->orderBy('sort_order_for_project')
            ->orderBy('name');
    }

    public function teamJobs()
    {
        return Job::where('project_id', $this->id)
            ->orWhere('team_project_id', $this->id)
            ->orderBy('sort_order_for_project')
            ->orderBy('name')
            ->get();
    }

    public function isArchived() {
        return $this->is_archived == true;
    }

    public function isOwn() {
        return $this->user_id == auth()->id();
    }
}
