<?php

namespace App\Models\Jobs;

use App\Http\Controllers\Interfaces\MorphTo;
use App\Models\File;
use App\Models\Jobs\Applications;
use App\Models\Jobs\Bookmarks;
use App\Models\Jobs\Categories;
use App\Models\Jobs\DifficultyLevel;
use App\Models\Jobs\JobCategories;
use App\Models\Jobs\Skills;
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
        return $this->belongsToMany(Categories::class, 'job_categories', 'job_id', 'category_id');
    }

    function difficulty()
    {
        return $this->belongsTo(DifficultyLevel::class, 'difficulty_level_id', 'id');
    }

    function applications()
    {
        return $this->hasMany(Applications::class, 'job_id', 'id');
    }

    function application()
    {
        return $this->hasOne(Applications::class, 'job_id', 'id')->where('user_id', Auth::user()->id);
    }

    function bookmarks()
    {
        return $this->hasMany(Bookmarks::class, 'job_id', 'id');
    }

    function jobCategories()
    {
        return $this->hasMany(JobCategories::class, 'job_id', 'id');
    }

    function skills(){
        return $this->belongsToMany(Skills::class,'job_skills','job_id','skill_id','id');
    }

    public function hasSkill(Skills $skill)
    {
        return $this->skills()->get()->contains($skill);
    }

    public function hasCategory(Categories $categories)
    {
        return $this->categories()->get()->contains($categories);
    }

    public function hasLogin($login)
    {
        /** @var User $user */
        $user = User::query()->where('username', $login)->first();

        if(!isset($user)) return false;

        return $this->applications()->where(function ($query) use ($user) {
            $query->where('user_id', $user->id)->where('job_id', $this->id);
        })->exists();
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
