<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Organization extends Model
{
    protected $fillable = ['user_id', 'name', 'description', 'logo', 'slug', 'status'];

    /**
     * Scope a query to only include approved organisations.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include unapproved organisations.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope a query to only include unapproved organisations.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMy($query)
    {
        return $query->where('user_id', auth()->id());
    }

    /**
     * Scope a query to only include unapproved organisations.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeModeration($query)
    {
        return $query->where('status', 'moderation');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'organization_users');
    }

    public function logo()
    {
        $logoDir = '/storage/images/organization/';

        if ($this->logo) {
            return $logoDir . $this->logo;
        }

        return $logoDir . 'default.png';
    }

    public function addLogo($file)
    {
        $storageDir = '/public/images/organization/';

        if (!Storage::exists($storageDir)) {
            Storage::makeDirectory($storageDir);
        }

        $file->store($storageDir);

        $this->logo = $file->hashName();

        $this->save();
    }
}
