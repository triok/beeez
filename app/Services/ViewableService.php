<?php

namespace App\Services;

use App\Http\Controllers\Helpers\Period;
use App\Http\Controllers\Interfaces\ViewableService as ViewableServiceContract;
use Carbon\Carbon;

class ViewableService implements ViewableServiceContract
{
    /**
     * Get the views count based upon the given arguments.
     *
     * @param \Illuminate\Database\Eloquent\Model $viewable
     * @param null $period
     * @return int
     */
    public function getViewsCount($viewable, $period = null)
    {
        $period = $period ?? Period::create();

        $viewsCount = $this->countViews($viewable, $period->getStartDateTime(), $period->getEndDateTime());

        return $viewsCount;
    }

    /**
     * Count the views based upon the given arguments.
     *
     * @param  \Illuminate\Database\Eloquent\Model $viewable
     * @param  \DateTime $startDateTime
     * @param  \DateTime $endDateTime
     * @return int
     */
    public function countViews($viewable, $startDateTime = null, $endDateTime = null)
    {
        // Create new Query Builder instance of the views relationship
        $query = $viewable->views();
        // Apply the following date filters
        if ($startDateTime && ! $endDateTime) {
            $query->where('created_at', '>=', $startDateTime);
        } elseif (! $startDateTime && $endDateTime) {
            $query->where('created_at', '<=', $endDateTime);
        } elseif ($startDateTime && $endDateTime) {
            $query->whereBetween('created_at', [$startDateTime, $endDateTime]);
        }

        $viewsCount = $query->count();

        return $viewsCount;
    }

    /**
     * Store a new view.
     *
     * @param \Illuminate\Database\Eloquent\Model $viewable
     * @return bool
     */
    public function addViewTo($viewable)
    {
        $view = $viewable->views()->create([
            'user_id' => auth()->id(),
            'viewable_id' => $viewable->getKey(),
            'viewable_type' => $viewable->getMorphClass(),
            'created_at' => Carbon::now(),
        ]);

        $view->save();

        return true;
    }

    /**
     * Remove all views from a viewable model.
     *
     * @param \Illuminate\Database\Eloquent\Model $viewable
     */
    public function removeModelViews($viewable)
    {
        $viewable->views()->where([
            'viewable_id' => $viewable->getKey(),
            'viewable_type' => $viewable->getMorphClass(),
        ])->delete();
    }
}