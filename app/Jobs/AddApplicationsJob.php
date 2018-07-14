<?php

namespace App\Jobs;

use App\Models\Jobs\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AddApplicationsJob implements ShouldQueue
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
        if(!isset(request()->user)) return;

        $this->job->applications()->create([
            'user_id'   => request()->user,
            'status'    => 'pending',
            'job_price' => request()->price
        ]);

    }
}
