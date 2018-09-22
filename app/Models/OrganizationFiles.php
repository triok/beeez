<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationFiles extends Model
{
    protected $fillable = ['title', 'path'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function link()
    {
        return '/storage/files/' . $this->organization->user_id . '/' . $this->path;
    }
}
