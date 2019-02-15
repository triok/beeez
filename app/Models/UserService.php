<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserService extends Model
{
    protected $fillable = ['user_id', 'name'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}