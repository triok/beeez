<?php

namespace App\Transformers;

class TeamTransformer extends Transformer
{
    /**
     * @param $team
     * @return array
     */
    public function transform($team)
    {
        return [
            "name" => $team->name,
            "type" => $team->type->name,
            "route" => route('teams.show', $team),
            "created_at" => $team->created_at->format('Y-m-d H:i:s'),

            "owner" => [
                "id" => $team->user_id,
                "name" => $team->user->name,
                "route" => route('peoples.show', $team->user)
            ],
        ];
    }
}