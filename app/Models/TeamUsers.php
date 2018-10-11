<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class TeamUsers extends Model
{
    protected $fillable = ['user_id', 'team_id', 'is_approved', 'is_admin', 'position'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
