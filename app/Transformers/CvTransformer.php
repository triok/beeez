<?php

namespace App\Transformers;

use App\Models\Cv;

class CvTransformer extends Transformer
{
    /**
     * @param $cv
     * @return array
     */
    public function transform($cv)
    {
        $cv = Cv::find($cv->id);

        return [
            "id" => $cv->id,
            "name" => $cv->name,
            "name" => $cv->name,
            "email" => $cv->email,
            "phone" => $cv->phone,
            "status" => \Lang::get('cvs.' . $cv->status),
            "created_at" => $cv->created_at->format('Y-m-d H:i:s'),
            'route' => route('vacancies.show', [$cv->vacancy, $cv]),
            "vacancy" => [
                'name' => $cv->vacancy->name,
                'route' => route('vacancies.show', $cv->vacancy),
            ],
        ];
    }
}