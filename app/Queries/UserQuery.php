<?php


namespace App\Queries;

use App\User;

class UserQuery
{
    public static function findByLogin($login)
    {
        if (!isset($login) || $login == '') return;

        return User::query()->where('username', 'like', '%' . $login . '%');
    }

    public static function users()
    {
        return User::query()->where('id', '<>', auth()->id());
    }
}