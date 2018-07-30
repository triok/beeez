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

        return view('admin.peoples-index', compact('users'));
    }

    public function show(User $user)
    {
        $user->socialLinks = $user->socialLinks();

        return  view('admin.peoples-show', compact('user'));
    }

//    public function update(UpdateUserRequest $request, User $user)
//    {
//        // TODO this place
//        if (isset($request->avatar)) {
//
//            $file = request()->file('avatar');
//            $name = time().$file->getClientOriginalName();
////            request()->file->storeAs('/public/jobs/upload', $name);
//            $request['avatar'] = $name;
//
//        }
//        dd($request->all());
//
//        $user->update($request);
//
//
//        flash()->success('Avatar success updated!');
//
//        return redirect()->back();
//    }

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
