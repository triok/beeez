<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = ['organization_id', 'name', 'description'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'department_users');
    }
}
