<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        /** @var User $users */
        $users = User::query()->get();

        if ($request->get('q')) {
            $users = User::login($request->q)->take(10)->get();
        }

        if($request->get('source') == 'dataTable') {
            return response()->json(['data' => $users]);
        }

        return response()->json($users);
    }
}
