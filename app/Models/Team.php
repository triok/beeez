<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Team extends Model
{
    protected $fillable = ['user_id', 'name', 'description', 'team_type_id', 'logo', 'slug'];

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
        return $this->belongsToMany(User::class, 'team_users');
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function type()
    {
        return $this->belongsTo(TeamType::class, 'team_type_id');
    }

    public function logo()
    {
        $logoDir = '/storage/images/teams/';

        if ($this->logo) {
            return $logoDir . $this->logo;
        }

        return $logoDir . 'default.png';
    }

    public function addLogo($file)
    {
        $storageDir = '/public/images/teams/';

        if (!Storage::exists($storageDir)) {
            Storage::makeDirectory($storageDir);
        }

        $file->store($storageDir);

        $this->logo = $file->hashName();

        $this->save();
    }
}
