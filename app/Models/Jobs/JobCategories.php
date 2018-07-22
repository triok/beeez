<?php

namespace App\Models\Jobs;

use Illuminate\Database\Eloquent\Model;

class JobCategories extends Model
{
    /**
     * @var array
     */
//    protected $fillable=['category_id','job_id'];

    protected $guarded = ['id'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    function jobs()
    {
        return $this->belongsTo(Job::class,'job_id','id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    function categories()
    {
        return $this->belongsToMany(Category::class,'categor_id','id');
    }
}
