<?php

namespace App\Http\Controllers\Traits;


use Illuminate\Http\UploadedFile;
use Exception;
use Illuminate\Support\Facades\Storage;

trait Avatarable
{
    public function addAvatar(UploadedFile $file)
    {
        if ($this->avatar()->where('imageable_id', $this->id)->exists()) {
            throw new Exception();
        }

        if (!Storage::exists($this->getAvatarDir())) {
            Storage::makeDirectory($this->getAvatarDir());
        }

        $file->store($this->getAvatarDir());
        /** @var Image $photo */
        $photo = $this->avatar()->create(['image' => $this->getAvatarDir() . $file->hashName()]);

        return $photo;
    }

    public function updateAvatar(UploadedFile $file)
    {
        $filePath =  $this->getAvatarDir() . $this->avatar;

        if (Storage::exists($filePath)) Storage::delete($filePath);

        $avatar = $this->update(['avatar' => $file->hashName()]);
        $file->store($this->getAvatarDir());

        return $avatar;
    }

    public function getAvatarDir(): string
    {
        return '/public/images/';
    }

    public function getStorageDir(): string
    {
        return '/storage/images/';
    }
}