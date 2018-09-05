<?php

namespace App\Http\Controllers\API;

use App\Models\Team;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        if ($request->get('q')) {
            $teams = Team::where('name', 'LIKE', '%' . $request->q . '%')->take(10)->get();
        } else {
            $teams = Team::query()->take(10)->get();
        }

        return response()->json($teams);
    }
}
