<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CvFile extends Model
{
    protected $fillable = ['title', 'path'];

    public function cv()
    {
        return $this->belongsTo(Cv::class);
    }

    public function link()
    {
        return '/public/files/cv/' . $this->path;
    }
}