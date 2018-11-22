<?php

namespace App\Jobs;

use App\Models\Jobs\Job;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AddSubTasksJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    /** @var Job $job */
    protected $job;
    /** @var array $arSub */
    private $arSub;
    public function __construct(Job $job)
    {
        $this->job = $job;
        $this->arSub = array();
    }

    public function handle()
    {
        for($j = 2;$j<=10;$j++) {
            $this->arSub = array_filter(request()->all(), function ($k) use ($j) {
                return strpos($k, 'sub-' . $j) === 0;
            }, ARRAY_FILTER_USE_KEY);

            if(!empty($this->arSub)) {
                if (!isset($this->arSub['sub-' . $j . '-name']) || $this->arSub['sub-' . $j . '-name'] == '') continue;

                $this->create($this->arSub, $j);
            }
        }

        unset($this->arSub);
    }


    private function create(array $data, int $indx = 1)
    {
        //dd($data);
        /** @var Job $subJob */
        $subJob                      = new Job();
        $subJob->name                = $data['sub-'.$indx.'-name'];
        $subJob->desc                = $data['sub-'.$indx.'-desc'];
        $subJob->instructions        = $data['sub-'.$indx.'-instructions'];
        $subJob->access              = $data['sub-'.$indx.'-access'];
        $subJob->end_date            = Carbon::createFromFormat('d.m.Y H:i', $data['sub-'.$indx.'-end_date'])->format('Y-m-d H:i:s');
        $subJob->price               = $data['sub-'.$indx.'-price'] ?? 0;
        $subJob->difficulty_level_id = $data['sub-'.$indx.'-difficulty_level_id'];
        $subJob->status              = request()->has('draft')? config('enums.jobs.statuses.DRAFT'): config('enums.jobs.statuses.OPEN');
        $subJob->time_for_work       = $data['sub-'.$indx.'-time_for_work'];
        $subJob->parent_id           = $this->job->id;
        $subJob->save();

        if(isset($data['sub-'.$indx.'-categories'])) {
            $subJob->categories()->sync(array_values($data['sub-'.$indx.'-categories']));
        } else {
            dispatch(new AddCategoriesJob($subJob));
        }

        if(isset($data['sub-'.$indx.'-skills'])) {
            $subJob->skills()->sync(array_values($data['sub-'.$indx.'-skills']));
        }

        if(isset($data['sub-'.$indx.'-user'])) {
            $subJob->applications()->create([
                'user_id'   => $data['sub-'.$indx.'-user'],
                'status'    => 'pending',
                'job_price' => $data['sub-'.$indx.'-price'] ?? 0
            ]);
        } else {
            dispatch(new AddApplicationsJob($subJob));
        }

        if(isset($data['sub-'.$indx.'-tag'])) {
            $subJob->tag()->create(['value' => $data['sub-'.$indx.'-tag']]);
        }

        if(!empty(request()->files->get('sub-'.$indx.'-files'))) {

            $subJob->files()->delete();

            foreach (request()->files->get('sub-'.$indx.'-files') as $file) {
                $name = time().$file->getClientOriginalName();

                $subJob->files()->create([
                    'file'          => $name,
                    'size'          => $file->getSize() ?? 0,
                    'type'          => $file->getMimeType(),
                    'original_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        $this->addJobToProject($subJob);

        return $subJob;
    }

    protected function addJobToProject(Job $job) {
        if (request()->has('project_id')) {
            $project = Project::find(request()->get('project_id'));

            if($project) {
                $job->update(['project_id' => request()->get('project_id')]);
            } else {
                $job->update(['project_id' => null]);
            }
        }
    }
}
