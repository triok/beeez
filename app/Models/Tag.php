<?php

namespace App\Models;

use App\Models\Jobs\Jobs;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = ['id'];

    public function tageable()
    {
        return $this->morphTo();
    }

    public function jobs()
    {
        return $this->hasMany(Jobs::class, 'id', 'tageable_id');
    }
}
