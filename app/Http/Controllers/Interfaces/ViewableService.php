<?php

namespace App\Http\Controllers\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface ViewableService
{
    /**
     * Get the views count based upon the given arguments.
     *
     * @param  Model $viewable
     * @param null $period
     * @return int
     */
    public function getViewsCount($viewable, $period = null);

    /**
     * Store a new view.
     *
     * @param  Model  $viewable
     * @return bool
     */
    public function addViewTo($viewable);
    /**
     * Remove all views from a viewable model.
     *
     * @param  Model  $viewable
     * @return void
     */
    public function removeModelViews($viewable);

}