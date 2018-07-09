<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    protected $guarded = ['id'];

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('status');
    }
}
