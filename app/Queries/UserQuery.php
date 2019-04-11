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

    public static function allCountries()
    {
    	return User::query()->whereNotNull('country')->pluck('country')->unique();
    }
    public static function allCities()
    {
    	return User::query()->whereNotNull('city')->pluck('city')->unique();
    }    
}