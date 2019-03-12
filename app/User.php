<?php

namespace App;

use App\Http\Controllers\Traits\Avatarable;
use App\Http\Controllers\Traits\Commentable;
use App\Http\Controllers\Traits\Imageable;
use App\Models\Billing\Stripe;
use App\Models\Escrow\BeneficiaryCard;
use App\Models\Escrow\PayerCard;
use App\Models\Favorite;
use App\Models\File;
use App\Models\Image;
use App\Models\Jobs\Application;
use App\Models\Jobs\Bookmark;
use App\Models\Jobs\Job;
use App\Models\Jobs\Skill;
use App\Models\Message;
use App\Models\Organization;
use App\Models\OrganizationUsers;
use App\Models\Participant;
use App\Models\RoleUser;
use App\Models\Social;
use App\Models\Project;
use App\Models\Task;
use App\Models\Team;
use App\Models\TeamUsers;
use App\Models\Thread;
use App\Models\Traits\Favoritable;
use App\Models\UserExperience;
use App\Models\UserPortfolio;
use App\Models\UserService;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Laratrust\Traits\LaratrustUserTrait;
use Cmgmyr\Messenger\Traits\Messagable;
use Cmgmyr\Messenger\Models\Models;

class User extends Authenticatable
{
    use LaratrustUserTrait, Notifiable, Avatarable, Commentable, Messagable, Favoritable;

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
        return $this->hasMany(Bookmark::class,'user_id','id');
    }
    function bookmarked(){
        return $this->hasMany(Job::class,'job_id','id');
    }

    function jobs()
    {
        return $this->hasMany(Job::class);
    }

    function payerCards()
    {
        return $this->hasMany(PayerCard::class);
    }

    function payerCard()
    {
        return $this->payerCards()->first();
    }

    function beneficiaryCards()
    {
        return $this->hasMany(BeneficiaryCard::class);
    }

    function beneficiaryCard()
    {
        return $this->beneficiaryCards()->first();
    }

    function applications(){
        return $this->hasMany(Application::class,'user_id','id');
    }
    function skills(){
        return $this->belongsToMany(Skill::class,'user_skills','user_id','skill_id','id');
    }

    function appliedJobs(){
        return $this->belongsToMany(Job::class,'applications','user_id','job_id','id')->where('jobs.deleted_at','=',null);
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

    public function getAvatarDir()
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

    public function scopeFilterName($query, $value)
    {
        return $query->where('name', 'like', '%' . $value . '%');
    }

    public function services()
    {
        return $this->hasMany(UserService::class)
            ->orderBy('name');
    }

    public function experiences()
    {
        return $this->hasMany(UserExperience::class)
            ->orderByDesc('hiring_at');
    }

    public function portfolio()
    {
        return $this->hasMany(UserPortfolio::class)
            ->orderBy('name');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function projects()
    {
        return $this->hasMany(Project::class)
            ->where('is_temporary', false)
            ->orderBy('sort_order')
            ->orderBy('name');
    }

    public function teams()
    {
        return $this->hasMany(TeamUsers::class)->with('team');
    }

    public function ownTeams()
    {
        return $this->hasMany(Team::class);
    }

    public function proposalTeams()
    {
        $teamIds = $this->teams()
            ->where('is_admin', 1)
            ->pluck('team_id')
            ->toArray();

        return Team::where('user_id', $this->id)
            ->orWhereIn('id', $teamIds)
            ->get();
    }

    public function isTeamOwner(Team $team)
    {
        if($team->user_id == $this->id) {
            return true;
        }

        return false;
    }

    public function isTeamAdmin(Team $team)
    {
        if($this->isTeamOwner($team)) {
            return true;
        }

        if($team->users()->where('is_admin', 1)->count()) {
            return true;
        }

        return false;
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function isOnline()
    {
        return Cache::has('user-is-online-' . $this->id);
    }

    public function allUserTeams() {
        $teamIds = $this->teams()->pluck('team_id')->toArray();

        return Team::where('user_id', $this->id)
            ->orWhereIn('id', $teamIds);
    }

    public function allUserProjects() {
        $teamIds = $this->allUserTeams()->pluck('id')->toArray();

        $projectIds = Project::whereIn('team_id', $teamIds)
            ->where('is_temporary', false)
            ->pluck('id')
            ->toArray();

        return Project::where('user_id', $this->id)
            ->orWhereIn('id', $projectIds);
    }

    public function favoritedTeams()
    {
        $teamIds = TeamUsers::where('user_id', $this->id)->pluck('team_id')->toArray();

        $teams = Team::where('user_id', $this->id)
            ->orWhereIn('id', $teamIds)
            ->get();

        $teams = $teams->filter(function ($team) {
            return $team->isFavorited();
        });

        return $teams;
    }

    public function favoritedUsers()
    {
        $userIds = $this->favorited()
            ->where('favoritable_type', 'App\User')
            ->pluck('favoritable_id');

        return User::whereIn('id', $userIds)->get();
    }

    public function favorited()
    {
        return Favorite::where('user_id', $this->id);
    }

    // todo: fix "2"
    public function organizations2()
    {
        return $this->hasMany(Organization::class);
    }

    public function organizations()
    {
        return $this->hasMany(OrganizationUsers::class)
            ->with('organization');
    }

    public function addProject($attributes)
    {
        return $this->projects()->create($attributes);
    }

    /**
     * Returns the new messages count for user.
     *
     * @param null $thread_id
     * @return int
     */
    public function unreadMessagesCountForThread($thread_id)
    {
        $participant = Participant::where('user_id', auth()->user()->id)
            ->where('thread_id', '=', $thread_id)
            ->first();

        return Message::where('thread_id', '=', $thread_id)
            ->where('updated_at', '>', $participant->last_read)
            ->count();
    }

    public function isAdmin() {
        return $this->email == config('app.admin_email');
    }

    /**
     * Determines if user is an Owner of an Organization.
     *
     * @param Organization $organization
     * @return bool
     */
    public function isOrganizationOwner(Organization $organization)
    {
        if ($organization->user_id == $this->id) {
            return true;
        }

        return false;
    }

    /**
     * Determines if user is an Admin of an Organization.
     *
     * @param Organization $organization
     * @return bool
     */
    public function isOrganizationAdmin(Organization $organization)
    {
        if ($this->isOrganizationOwner($organization)) {
            return true;
        }

        if ($this->isOrganizationFullAccess($organization)) {
            return true;
        }

        $connection = OrganizationUsers::where('organization_id', $organization->id)
            ->where('user_id', $this->id)
            ->where('is_approved', true)
            ->where('is_admin', true)
            ->first();

        if ($connection && $connection->is_admin) {
            return true;
        }

        return false;
    }

    /**
     * Determines if user is an Owner of an Organization.
     *
     * @param Organization $organization
     * @return bool
     */
    public function isOrganizationFullAccess(Organization $organization)
    {
        if ($this->isOrganizationOwner($organization)) {
            return true;
        }

        $connection = OrganizationUsers::where('organization_id', $organization->id)
            ->where('user_id', $this->id)
            ->where('is_approved', true)
            ->where('is_owner', true)
            ->first();

        if ($connection && $connection->is_owner) {
            return true;
        }

        return false;
    }

    public function addPayerCard($attributes)
    {
        return $this->payerCards()->create([
            'platform_payer_id' => $attributes['PlatformPayerId'],
            'payer_payment_tool_id' => $attributes['PayerPaymentToolId'],
        ]);
    }

    public function addBeneficiaryCard($attributes)
    {
        return $this->beneficiaryCards()->create([
            'platform_beneficiary_id' => $attributes['PlatformBeneficiaryId'],
            'beneficiary_payment_tool_id' => $attributes['BeneficiaryPaymentToolId'],
        ]);
    }
}
