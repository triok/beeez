<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class StructureUsers extends Model
{
    protected $fillable = ['user_id', 'structures_id', 'position'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function structure()
    {
        return $this->belongsTo(Structure::class);
    }
}
