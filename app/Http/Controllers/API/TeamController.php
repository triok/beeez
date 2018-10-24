<?php

namespace App\Http\Controllers\API;

use App\Models\Team;
use App\Http\Controllers\Controller;
use App\Models\TeamUsers;
use App\Transformers\TeamTransformer;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    protected $transformer;

    public function __construct(TeamTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function index(Request $request)
    {
        if ($request->get('q')) {
            $teams = Team::where('name', 'LIKE', '%' . $request->q . '%')->take(10)->get();
        } else {
            $teams = Team::query()->take(10)->get();
        }

        return response()->json($teams);
    }

    public function search(Request $request)
    {
        $teams = Team::query();

        if ($request->get('type')) {
            $teams->where('team_type_id', $request->get('type'));
        }

        if ($request->get('user_id')) {
            $teamIds = TeamUsers::where('user_id', $request->get('user_id'))->pluck('team_id')->toArray();

            $teams->where('user_id', $request->get('user_id'))
                ->orWhereIn('id', $teamIds);
        }

        $teams = $teams->with('user')->with('type')->get();

        if ($request->has('favorite')) {
            $teams = $teams->filter(function ($team) {
                return $team->isFavorited();
            });
        }

        return response()->json(
            $this->transformer->transformCollection($teams)
        );
    }
}
