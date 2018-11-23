<?php

namespace App\Models;

use App\Models\Jobs\Job;
use App\Models\Traits\Favoritable;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use Favoritable;

    protected $fillable = ['user_id', 'team_id', 'structure_id', 'name', 'description', 'icon', 'is_archived', 'deadline_at'];

    protected $dates = ['deadline_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
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

    public function isArchived() {
        return $this->is_archived == true;
    }

    public function isOwn() {
        return $this->user_id == auth()->id();
    }
}
