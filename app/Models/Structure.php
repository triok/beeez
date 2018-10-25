<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Structure extends Model
{
    protected $fillable = ['organization_id', 'name', 'description'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function employees()
    {
        return $this->belongsToMany(User::class, 'structure_users');
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
