<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\Jobs\Skill;
use App\Models\UserSpeciality;
use App\Queries\UserQuery;
use App\User;

class PeopleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:read-users',['only'=>['index', 'show']]);
    }

    public function index()
    {
        $users = UserQuery::users()->paginate(request('count', 20));

        $specialities = UserSpeciality::get();

        $skills = Skill::pluck('name');

        $countries = UserQuery::allCountries();

        $cities = UserQuery::allCities();        

        return view('peoples.peoples-index', compact('users', 'skills','countries','cities','specialities'));
    }

    public function show(User $user)
    {
        $user->socialLinks = $user->socialLinks();

        return  view('peoples.peoples-show', compact('user'));
    }

    /**
     * Update a resource in storage.
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function favorite(User $user)
    {
        $user->setFavorited();

        flash()->success('Пользователь добавлен в избранные!');

        return redirect()->back();
    }

    /**
     * Update a resource in storage.
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function unfavorite(User $user)
    {
        $user->setUnfavorited();

        flash()->success('Пользователь удален с избранных!');

        return redirect()->back();
    }

    public function updateAvatar(User $user)
    {
        $this->validate(request(), ['avatar' => 'required|image|mimes:jpeg,jpg,png,gif']);

        if (request()->hasFile('avatar') && request()->file('avatar')->isValid()) {
            $user->updateAvatar(request()->file('avatar'));

            return response()->json([], 204);
        }

        return response()->json($user->fresh()->load('avatar'));
    }
}
