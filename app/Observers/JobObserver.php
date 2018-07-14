<?php

namespace App\Observers;

use App\Models\Jobs\Job;

class JobObserver
{
    public function creating(Job $job)
    {
        $job->user_id = auth()->id();
    }
}