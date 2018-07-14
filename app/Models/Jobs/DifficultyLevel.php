<?php

namespace App\Models\Jobs;

use Illuminate\Database\Eloquent\Model;

class DifficultyLevel extends Model
{
    protected $table='difficulty_level';

    protected $guarded = ['id'];

    function jobs(){
        return $this->hasMany(Job::class);
    }
}
