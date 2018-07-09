<?php

namespace App\Http\Controllers\Traits;

use App\Services\ViewableService;
use App\Models\View;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Viewable
{
    public function views() : MorphMany
    {
        return $this->morphMany(View::class, 'viewable');
    }

    public function getViews($period = null) :int
    {
        return app(ViewableService::class)
            ->getViewsCount($this, $period);
    }

    public function addView(): bool
    {
        return app(ViewableService::class)->addViewTo($this);
    }

    public function removeViews()
    {
        app(ViewableService::class)->removeModelViews($this);
    }
}