<?php

namespace App\Queries;


use App\Models\Jobs\Job;
use Illuminate\Database\Eloquent\Builder;

class JobQuery
{
    public static function onlyParentAndOpen()
    {
        return Job::query()->where(function (Builder $query) {
            $query->where('status',config('enums.jobs.statuses.OPEN'))
                ->whereNull('parent_id');
        });
    }

    public static function allForUser($user = null)
    {
        $user = $user ?? auth()->user();

        return Job::query()->where(function (Builder $query) use ($user){
            $query->where('user_id', $user->id);
        });
    }
}