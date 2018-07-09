<?php

namespace App\Models\Jobs;

use App\Http\Controllers\Interfaces\MorphTo;
use App\Models\File;
use App\Models\Tag;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\Viewable;


/**
 * @property  File files
 * @property  Categories categories
 */
class Jobs extends Model
{
    use SoftDeletes, Viewable;

    protected $fillable =[
        'name','desc','instructions','end_date','user_id','price','difficulty_level_id','status', 'time_for_work', 'created_at', 'access'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    function categories()
    {
        return $this->belongsToMany(\App\Models\Jobs\Categories::class, 'job_categories', 'job_id', 'category_id');
    }

    function difficulty()
    {
        return $this->belongsTo(\App\Models\Jobs\DifficultyLevel::class, 'difficulty_level_id', 'id');
    }

    function applications()
    {
        return $this->hasMany(\App\Models\Jobs\Applications::class, 'job_id', 'id');
    }

    function application()
    {
        return $this->hasOne(\App\Models\Jobs\Applications::class, 'job_id', 'id')->where('user_id', Auth::user()->id);
    }

    function bookmarks()
    {
        return $this->hasMany(\App\Models\Jobs\Bookmarks::class, 'job_id', 'id');
    }

    function jobCategories()
    {
        return $this->hasMany(\App\Models\Jobs\JobCategories::class, 'job_id', 'id');
    }

    function skills(){
        return $this->belongsToMany(\App\Models\Jobs\Skills::class,'job_skills','job_id','skill_id','id');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function tag()
    {
        return $this->morphOne(Tag::class, 'tageable');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * add currency symbol
     * @return string
     */
    function getFormattedPriceAttribute(){
        $price = $this->attributes['price'];
        return env('CURRENCY_SYMBOL') .$price;
    }

    function getDifficultyLevelId($value)
    {
        $diff = DifficultyLevel::find($value);
        if (count($diff) > 0) {
            return $diff->name;
        } else {
            return 'not set';
        }
    }

    function getPrettyStatusAttribute()
    {
        $status = strtolower($this->attributes['status']);
        //pending, approved,denied, closed
        switch ($status) {
            case "open":
                $color = "success";
                break;
            case "closed":
                $color = "danger";
                break;
            default:
                $color = "default";
                break;
        }
        return '<span class="label label-' . $color . '">' . ucwords($status) . '</span>';
    }

    function formatSkills($skills){
        $res="";
        foreach($skills as $skill){
            $res .='<span class="label label-default">'.$skill->name.'</span> ';
        }
        return $res;
    }
    function formatCats($cats){
        $res="";
        foreach($cats as $cat){
            $res .='<a href="/jobs/category/'.$cat->id.'"><span class="label label-default">'.$cat->name.'</span></a> ';
        }
        return $res;
    }
}
