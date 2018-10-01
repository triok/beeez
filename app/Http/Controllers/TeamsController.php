<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\Project;
use App\Models\Team;
use App\Models\TeamType;
use App\Models\TeamUsers;
use App\Queries\UserQuery;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeamsController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $teamIds = auth()->user()->teams()->pluck('team_id')->toArray();

        $teams = Team::where('user_id', auth()->id())
            ->orWhereIn('id', $teamIds)
            ->paginate(request('count', 20));

        return view('teams.index', compact('teams'));
    }

    /**
     * Display the specified resource.
     *
     * @param Team $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        $connections = TeamUsers::where('team_id', $team->id)->get();

        return view('teams.show', compact('team', 'connections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teamTypes = TeamType::all();

        $users = User::all();

        return view('teams.create', compact('teamTypes', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:200',
            'team_type_id' => 'required',
            'slug' => 'required|unique:teams',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,gif',
        ];

        $attributes = $request->all();

        $attributes['slug'] = str_slug($request->get('name', ''));

        $validator = Validator::make($attributes, $rules, ['unique' => 'The name has already been taken.']);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $attributes['user_id'] = auth()->user()->id;

        $team = Team::create($attributes);

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $team->addLogo($request->file('logo'));
        }

        $this->addConnection($request, $team);

        flash()->success('Team saved!');

        return redirect(route('teams.show', $team));
    }

    /**
     * Show the form for editing a resource.
     *
     * @param Team $team
     * @return \Illuminate\Http\Response
     */
    public function edit(Team $team)
    {
        if ($team->user_id != auth()->user()->id) {
            flash()->error('Access denied!');

            return redirect()->back();
        }

        $teamTypes = TeamType::all();

        $connections = TeamUsers::where('team_id', $team->id)->get();

        $users = User::all();

        return view('teams.edit', compact('team', 'teamTypes', 'connections', 'users'));
    }

    /**
     * Update a resource in storage.
     *
     * @param Request $request
     * @param Team $team
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function update(Request $request, Team $team)
    {
        if ($team->user_id != auth()->user()->id) {
            flash()->error('Access denied!');

            return redirect()->back();
        }

        $rules = [
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,gif',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $team->description = $request->get('description', '');
        $team->save();

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $team->addLogo($request->file('logo'));
        }

        $this->addConnection($request, $team);

        flash()->success('Team updated!');

        return redirect(route('teams.show', $team));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function projects()
    {
        $teamIds = auth()->user()->teams()->pluck('team_id')->toArray();

        $teams = Team::where('user_id', auth()->id())
            ->orWhereIn('id', $teamIds)
            ->get();

        $teamProjects = [];

        foreach ($teams as $team) {
            $projects = Project::where('team_id', $team->id)->get();

            $teamProjects[$team->id] = $projects;
        }

        $teamSelected = request('team_id', ($teams->first() ? $teams->first()->id : 0));

        return view('teams.projects', compact('projects', 'teams', 'teamProjects', 'teamSelected'));
    }

    /**
     * Add connections.
     *
     * @param Request $request
     * @param Team $team
     * @throws \Exception
     */
    protected function addConnection(Request $request, Team $team)
    {
        if ($request->has('connections')) {
            $connectionIds = TeamUsers::where('team_id', $team->id)->pluck('position', 'user_id')->toArray();

            foreach ($request->get('connections') as $user_id => $connection) {
                if (isset($connectionIds[$user_id])) {
                    if ($connectionIds[$user_id] != $connection['position']) {
                        TeamUsers::where('team_id', $team->id)
                            ->where('user_id', $user_id)
                            ->update(['position' => $connection['position']]);
                    }

                    unset($connectionIds[$user_id]);
                } else {
                    TeamUsers::create([
                        'team_id' => $team->id,
                        'user_id' => $user_id,
                        'position' => $connection['position']
                    ]);
                }
            }

            foreach ($connectionIds as $user_id => $position) {
                TeamUsers::where('team_id', $team->id)
                    ->where('user_id', $user_id)
                    ->delete();
            }
        } else {
            TeamUsers::where('team_id', $team->id)
                ->delete();
        }
    }
}
