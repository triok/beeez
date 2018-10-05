<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $fillable = ['user_id'];

    /**
     * Get all of the owning favoritable models.
     */
    public function favoritable()
    {
        return $this->morphTo();
    }
}
