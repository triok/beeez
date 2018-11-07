<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Cv extends Model
{
    protected $fillable = ['user_id', 'name', 'email', 'phone', 'about'];

    public function vacancy()
    {
        return $this->belongsTo(Vacancy::class);
    }

    public function files()
    {
        return $this->hasMany(CvFile::class);
    }

    public function addFile($file)
    {
        $storageDir = '/public/files/cv/';

        if (!Storage::exists($storageDir)) {
            Storage::makeDirectory($storageDir);
        }

        $file->store($storageDir);

        $this->files()->create([
            'title' => $file->getClientOriginalName(),
            'path' => $file->hashName()
        ]);
    }
}
