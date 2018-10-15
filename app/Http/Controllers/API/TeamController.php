<?php

namespace App\Http\Controllers\API;

use App\Models\Team;
use App\Http\Controllers\Controller;
use App\Transformers\TeamTransformer;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        if ($request->get('q')) {
            $response = Team::where('name', 'LIKE', '%' . $request->q . '%')->take(10)->get();
        } else {
            if ($request->get('type')) {
                $teams = Team::where('team_type_id', $request->get('type'))->with('user')->with('type')->get();
            } else {
                $teams = Team::query()->with('user')->with('type')->get();
            }

            $transformer = new TeamTransformer();

            $response = $transformer->transformCollection($teams);
        }

        return response()->json($response);
    }
}
