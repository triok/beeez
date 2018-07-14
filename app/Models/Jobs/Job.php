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
 * @property  Category categories
 * @property  Job jobs
 * @property  Skill skills
 * @property  Application applications
 * @property  integer id
 * @property  string name
 * @property  string desc
 * @property  string instructions
 * @property  string access
 * @property  string end_date
 * @property  integer user_id
 * @property  double price
 * @property  integer difficulty_level_id
 * @property  integer time_for_work
 * @property  string status
 * @property  integer parent_id
 */

class Job extends Model
{
    use SoftDeletes, Viewable;

//    protected $fillable =[
//        'name','desc','instructions','end_date','user_id','price','difficulty_level_id','status', 'time_for_work', 'created_at', 'access'
//    ];
    protected $guarded = ['id'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'job_categories', 'job_id', 'category_id')->withTimestamps();
    }
    public function skills()
    {
        return $this->belongsToMany(Skill::class,'job_skills','job_id','skill_id')->withTimestamps();
    }

    public function jobs()
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    function difficulty()
    {
        return $this->belongsTo(DifficultyLevel::class, 'difficulty_level_id', 'id');
    }

    function applications()
    {
        return $this->hasMany(Application::class);
    }

    function application()
    {
        return $this->hasOne(Application::class, 'job_id', 'id')->where('user_id', Auth::user()->id);
    }

    function bookmarks()
    {
        return $this->hasMany(Bookmarks::class, 'job_id', 'id');
    }

    function jobCategories()
    {
        return $this->hasMany(JobCategories::class, 'job_id', 'id');
    }



    public function hasSkill(Skill $skill)
    {
        return $this->skills()->get()->contains($skill);
    }

    public function hasCategory(Category $categories)
    {
        return $this->categories()->get()->contains($categories);
    }

    public function hasLogin($id)
    {
        /** @var User $user */
        $user = User::query()->whereKey($id)->first();

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

    public static function getAllAttributes()
    {
        return ['name', 'desc', 'instructions', 'access', 'end_date', 'user_id', 'price',
            'difficulty_level_id', 'time_for_work', 'status', 'parent_id'];
    }
}