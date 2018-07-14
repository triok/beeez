<?php

namespace App\Jobs;

use App\Models\Jobs\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AddTagJob implements ShouldQueue
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
        if (!isset(request()->tag) || !array_key_exists(request()->tag, config('tags.tags'))) return;

        $this->job->tag()->create(['value' => request()->tag]);
    }
}
