<?php

namespace App;

use App\Http\Controllers\Traits\Avatarable;
use App\Http\Controllers\Traits\Imageable;
use App\Models\Billing\Stripe;
use App\Models\Image;
use App\Models\Jobs\Applications;
use App\Models\Jobs\Bookmarks;
use App\Models\Jobs\Jobs;
use App\Models\Jobs\Skills;
use App\Models\RoleUser;
use App\Models\Social;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use LaratrustUserTrait, Notifiable, Avatarable;

    protected $guarded = ['id'];
    protected $links;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','stripe_secret_key'
    ];

    function bookmarks(){
        return $this->hasMany(Bookmarks::class,'user_id','id');
    }
    function bookmarked(){
        return $this->hasMany(Jobs::class,'job_id','id');
    }

    function jobs()
    {
        return $this->hasMany(Jobs::class);
    }

    function applications(){
        return $this->hasMany(Applications::class,'user_id','id');
    }
    function skills(){
        return $this->belongsToMany(Skills::class,'user_skills','user_id','skill_id','id');
    }

    function appliedJobs(){
        return $this->belongsToMany(Jobs::class,'applications','user_id','job_id','id')->where('jobs.deleted_at','=',null);
    }
    function role(){
        return $this->belongsToMany(RoleUser::class,'role_user','role_id','user_id','id');
    }

    public function socials()
    {
        return $this->belongsToMany(Social::class)->withPivot('status', 'link');
    }

    function getRegisteredOnAttribute(){
        return date('d M, Y',strtotime($this->attributes['created_at']));
    }

    function stripe(){
        return $this->belongsTo(Stripe::class);
    }

    function getStripeSecretKeyAttribute($value){
        if($value==null){
            return '';
        }
        try {
            return decrypt($value);
        } catch (DecryptException $e) {
            return '';
        }
    }
    public function avatar()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function getAvatarDir(): string
    {
        return '/public/avatars/' . $this->id . '/';
    }

    public function getStorageDir()
    {
       if (preg_match('~(default)\.(png|jpeg|jpg|gif)~', $this->avatar)) {

           return '/storage/avatars/';
       }
        return '/storage/avatars/' . $this->id . '/';
    }

    public function socialLinks()
    {
        $this->links = collect();

        $this->links->put('facebook', ['obj' => $this->socials()->where('slug', 'facebook')->first(), 'title' => 'Facebook']);
        $this->links->put('instagram', ['obj' => $this->socials()->where('slug', 'instagram')->first(), 'title' => 'Instagram']);
        $this->links->put('linkedin', ['obj' => $this->socials()->where('slug', 'linkedin')->first(), 'title' => 'LinkedIn']);

        return $this->links;
    }

    public function scopeLogin($query, $value)
    {
        return $query->where('username', 'like', '%' . $value . '%');
    }
}
