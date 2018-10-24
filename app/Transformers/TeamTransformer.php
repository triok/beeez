<?php

namespace App\Transformers;

use App\Models\Team;

class TeamTransformer extends Transformer
{
    /**
     * @param $team
     * @return array
     */
    public function transform($team)
    {
        $team = Team::find($team->id);

        return [
            "id" => $team->id,
            "name" => $team->name,
            "type" => $team->type->name,
            "slug" => $team->slug,
            "is_favorited" => $team->isFavorited(),
            "route" => route('teams.show', $team),
            "is_owner" => (auth()->check() && auth()->id() == $team->user_id) ? true : false,
            "created_at" => $team->created_at->format('Y-m-d H:i:s'),

            "owner" => [
                "id" => $team->user_id,
                "name" => $team->user->name,
                "route" => route('peoples.show', $team->user)
            ],
        ];
    }
}