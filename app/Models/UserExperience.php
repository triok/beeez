<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserExperience extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
