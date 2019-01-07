<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class StructureUsers extends Model
{
    protected $fillable = ['user_id', 'structure_id', 'position', 'is_approved',
        'can_add_user', 'can_add_project', 'can_add_job', 'can_see_all_projects', 'can_add_user_to_project'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function structure()
    {
        return $this->belongsTo(Structure::class);
    }

    public function isAccess()
    {
        if($this->can_add_user ||
            $this->can_add_project ||
            $this->can_add_job ||
            $this->can_see_all_projects ||
            $this->can_add_user_to_project) {

            return true;
        }

        return false;
    }
}
