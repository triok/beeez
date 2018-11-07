<?php

namespace App\Transformers;

use App\Models\Vacancy;

class VacancyTransformer extends Transformer
{
    /**
     * @param $vacancy
     * @return array
     */
    public function transform($vacancy)
    {
        $vacancy = Vacancy::find($vacancy->id);

        return [
            "id" => $vacancy->id,
            "name" => $vacancy->name,
            "route" => route('vacancies.show', $vacancy),
            "organization" => [
                'name' => $vacancy->organization->name,
                'route' => route('organizations.show', $vacancy->organization),
            ],
            "specialization" => \Lang::get('vacancies.specialization_' . $vacancy->specialization),
            "responsibilities" => $vacancy->responsibilities,
            "conditions" => $vacancy->conditions,
            "requirements" => $vacancy->requirements,

            "total_views" => $vacancy->total_views,
            "total_responses" => $vacancy->total_responses,

            "published_at" => ($vacancy->published_at ? $vacancy->published_at->format('Y-m-d') : null),

            "is_favorited" => $vacancy->isFavorited(),
            "is_added_cv" => (bool)$vacancy->cvs()->where('user_id', auth()->id())->count(),
        ];
    }
}