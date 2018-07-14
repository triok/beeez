<?php

namespace App\Jobs;

use App\Models\Jobs\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Session;

class AddFilesJob implements ShouldQueue
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
        if (!Session::has('job.files')) return;

        $this->job->files()->delete();

        foreach (Session::get('job.files') as $file) {
            $this->job->files()->create([
                'file'          => $file['file'],
                'size'          => $file['size'],
                'type'          => $file['type'],
                'original_name' => $file['original_name'],
            ]);
        }
        Session::forget('job.files');

    }
}
