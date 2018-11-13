<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class OrganizationUsers extends Model
{
    protected $fillable = ['user_id', 'organization_id', 'position', 'is_admin'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
