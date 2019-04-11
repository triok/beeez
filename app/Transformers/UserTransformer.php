<?php

namespace App\Transformers;

use App\User;

class UserTransformer extends Transformer
{
    /**
     * @param $team
     * @return array
     */
    public function transform($user)
    {
        $user = User::find($user->id);

        return [
            "id" => $user->id,
            "name" => $user->name,
            "username" => $user->username,
            "speciality" => ($user->speciality),
            "route" => route('peoples.show', $user),
            "rating_positive" => $user->rating_positive,
            "rating_negative" => $user->rating_negative,
            "is_favorited" => $user->isFavorited(),
            "created_at" => $user->created_at->format('Y-m-d H:i:s'),
        ];
    }
}