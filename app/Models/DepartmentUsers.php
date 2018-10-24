<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class DepartmentUsers extends Model
{
    protected $fillable = ['user_id', 'department_id', 'position'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
