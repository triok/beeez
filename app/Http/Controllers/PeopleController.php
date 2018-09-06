<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
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

        return view('peoples.peoples-index', compact('users'));
    }

    public function show(User $user)
    {
        $user->socialLinks = $user->socialLinks();

        return  view('peoples.peoples-show', compact('user'));
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
