<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class TeamUsers extends Model
{
    protected $fillable = ['user_id', 'team_id', 'position'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
