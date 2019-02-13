<?php

namespace App\Transformers;

use App\Models\Jobs\Job;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Jobs\TimeForWork;

class JobTransformer extends Transformer
{
    /**
     * @param $job
     * @return array
     */
    public function transform($job)
    {
        $job = Job::find($job->id);

        return [
            "id" => $job->id,
            "name" => $job->name,
            "price" => $job->formattedPrice,
            "time_for_work" => TimeForWork::find($job->time_for_work),
            "status" => $job->status,
            "application" => $job->application,
            "client" => $job->user->name,
            "client_id" => $job->user->id,
            "client_rating_positive" => $job->user->rating_positive,
            "client_rating_negative" => $job->user->rating_negative,
            "applications_count" => count($job->applications),
            "comment" => $this->getComment($job),
            "auth_check" => Auth::check(),
            "allow_apply" => $this->checkAllowApply($job),
            "ended" => (Carbon::now() > $job->end_date),
            "end_date" => $job->end_date->format('d-m-Y H:i'),
            "created_at" => $job->created_at->format('d M Y H:i'),
            "skills" => $job->skills,
        ];
    }
    protected function checkAllowApply($job) {
        if(Carbon::now() > $job->end_date) {
            return false;
        }

        if($job->status != 'open') {
            return false;
        }

        // if($job->user_id == Auth::id()) {
        //     return false;
        // }

        return true;
    }


    private function formatDate($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }

    private function getComment($job)
    {
        if ($job->status == config('enums.jobs.statuses.IN_REVIEW') && isset($job->application)) {
            return '<p class="label label-danger">Your task is under review</p>';
        }

        return '';
    }
}