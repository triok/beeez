<?php

namespace App\Jobs;

use App\Models\Jobs\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AddCategoriesJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    /** @var Job $job */
    protected $job;

    public function __construct(Job $job)
    {
        $this->job = $job;
    }

    public function handle()
    {
        if(!isset(request()->categories)) return;

        $this->job->categories()->sync(array_values(request()->categories));
    }
}
