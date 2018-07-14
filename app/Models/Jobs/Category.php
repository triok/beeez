<?php

namespace App\Models\Jobs;

use App\Models\Jobs\Job;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Category subcategories
 * @property Category parent
 * @property Job jobs
 */

class Category extends Model
{
    protected $guarded = ['id'];

    function jobs()
    {
        return $this->belongsToMany(Job::class,'job_categories','category_id','job_id','id');
    }
    public function openJobs()
    {
        return $this->belongsToMany(Job::class,'job_categories','category_id','job_id','id')->where('status','open');
    }

    public function subcategories()
    {
        return $this->hasMany(static::class, 'parent_id');
    }
    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id');
    }


}
