<?php

namespace App\Transformers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class JobTransformer extends Transformer
{
    /**
     * @param $job
     * @return array
     */
    public function transform($job)
    {
        return [
            "id" => $job->id,
            "name" => $job->name,
            "price" => $job->formattedPrice,
            "time_for_work" => $job->time_for_work,
            "status" => $job->status,
            "application" => $job->application,
            "applications_count" => count($job->applications),
            "comment" => $this->getComment($job),
            "auth_check" => Auth::check(),
            "allow_apply" => $this->checkAllowApply($job),
            "ended" => (Carbon::now() > $job->end_date),
            "end_date" => $this->formatDate($job->end_date),
            "created_at" => $job->created_at,
        ];
    }


    protected function checkAllowApply($job) {
        if(Carbon::now() > $job->end_date) {
            return false;
        }

        if($job->status != 'open') {
            return false;
        }

        if($job->user_id == Auth::id()) {
            return false;
        }

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