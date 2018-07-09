<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    public function fileable()
    {
        return $this->morphTo();
    }

    public function getUploadJobPathAttribute()
    {
        return '/public/jobs/upload/';
    }
}
