<?php

namespace App\Jobs;

use App\Models\Jobs\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Session;

class AddFilesJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    protected $job;
    protected $taskId;

    public function __construct(Job $job, $taskId = 0)
    {
        $this->job = $job;
        $this->taskId = (int)$taskId;
    }

    public function handle()
    {
        $key = $this->getKey();

        if (!Session::has($key)) return;

        $this->job->files()->delete();

        foreach (Session::get($key) as $file) {
            $this->job->files()->create([
                'file'          => $file['file'],
                'size'          => $file['size'],
                'type'          => $file['type'],
                'original_name' => $file['original_name'],
            ]);
        }

        Session::forget($key);
    }

    protected function getKey()
    {
        return 'job.files' . $this->taskId;
    }
}
