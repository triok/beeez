<?php

namespace App\Jobs;

use App\Models\Jobs\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AddSkillsJob implements ShouldQueue
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
        if(!isset(request()->Skills)) return;

        $skills = explode(',', request()->Skills);

        // foreach ($skills as $skill) {
        //         UserSkills::firstOrCreate(['user_id' => Auth::user()->id, 'skill_id' => $skill]);
        //     }

        // $this->job->Skills()->sync(array_values(request()->Skills));
        $this->job->Skills()->sync(array_values($skills));

    }
}
