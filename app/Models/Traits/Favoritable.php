<?php

namespace App\Models\Traits;

use App\Models\Favorite;

trait Favoritable
{
    public function favorites()
    {
        return $this->morphMany(Favorite::class, 'favoritable');
    }

    public function isFavorited()
    {
        return (boolean)$this->favorites()
            ->where('user_id', auth()->id())
            ->count();
    }

    public function setFavorited()
    {
        if (!$this->isFavorited()) {
            $this->favorites()->save(new Favorite(['user_id' => auth()->id()]));
        }
    }

    public function setUnfavorited()
    {
        if ($this->isFavorited()) {
            $this->favorites()->where(['user_id' => auth()->id()])->delete();
        }
    }
}