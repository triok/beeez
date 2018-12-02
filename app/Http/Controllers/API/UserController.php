<?php

namespace App\Http\Controllers\API;

use App\Transformers\UserTransformer;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $transformer;

    public function __construct(UserTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

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

    public function search(Request $request)
    {
        if ($request->has('name')) {
            $users = User::filterName($request->get('name'))->get();
        } else {
            $users = User::all();
        }

        if ($request->has('favorite')) {
            $users = $users->filter(function ($user) {
                return $user->isFavorited();
            });
        }

        return response()->json(
            $this->transformer->transformCollection($users)
        );
    }
}
