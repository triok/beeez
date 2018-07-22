<?php

namespace App\Jobs;

use App\Models\Jobs\Job;
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
        for($j = 1; $j <=10; $j++) {

            $this->arSub = array_filter(request()->all(), function ($k) use ($j) {
                return strpos($k, 'sub-' . $j) === 0;

            }, ARRAY_FILTER_USE_KEY); //ARRAY_FILTER_USE_BOTH

            if(empty($this->arSub)) break;
            if (!isset($this->arSub['sub-' . $j . '-name']) || $this->arSub['sub-' . $j . '-name'] == '') continue;

            $this->create($this->arSub, $j);
        }
        unset($this->arSub);
    }

    private function create(array $data, int $indx = 1)
    {
        /** @var Job $subJob */
        $subJob                      = new Job();
        $subJob->name                = $data['sub-'.$indx.'-name'];
        $subJob->desc                = $data['sub-'.$indx.'-desription'];
        $subJob->instructions        = $data['sub-'.$indx.'-instruction'];
        $subJob->access              = $data['sub-'.$indx.'-access'];
        $subJob->end_date            = $data['sub-'.$indx.'-end_date'] ?? Carbon::now()->addDay(1);
        $subJob->price               = $data['sub-'.$indx.'-price'] ?? 0;
        $subJob->difficulty_level_id = $data['sub-'.$indx.'-difficulty_level'];
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

        return $subJob;
    }
}
