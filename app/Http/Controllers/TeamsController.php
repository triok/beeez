<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectUsers;
use App\Models\Team;
use App\Models\TeamType;
use App\Models\TeamUsers;
use App\Notifications\TeamUserNotification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $teamTypes = TeamType::orderBy('name')->pluck('name', 'id');
            
        return view('teams.index', compact('teamTypes'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function myteams(Team $teams) 
    {
        $teamTypes = TeamType::orderBy('name')->pluck('name', 'id');

        $teamIds = auth()->user()->teams()->pluck('team_id')->toArray(); 

        $teams = Team::where('user_id', auth()->id())
            ->orWhereIn('id', $teamIds)
            ->get();
        
        $teamProjects = [];

        foreach ($teams as $team) {
            $projects = Project::where('team_id', $team->id)->orderBy('sort_order')->orderBy('name')->get();
            $teamProjects[$team->id] = $projects;
            $AdminName = User::select('username','id')->where('id', $team->user_id)->first();              
        }

        $teamSelected = request('team_id', ($teams->first() ? $teams->first()->id : 0));

        

        return view('teams.myteams', compact('teamTypes','teams','projects','teamProjects', 'teamSelected','AdminName' ));
    }

    /**
     * Display the specified resource.
     *
     * @param Team $team
     * @return \Illuminate\Http\Response
     */
    public function show(Team $team)
    {
        $userIsAdmin = $this->userIsTeamAdmin($team);

        $userIsConnected = $this->userIsConnected($team);

        $connections = TeamUsers::where('team_id', $team->id)->get();

        return view('teams.show', compact('team', 'connections', 'userIsAdmin', 'userIsConnected'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $teamTypes = TeamType::all();

        $users = User::where('id', '!=', auth()->id())->get();

        return view('teams.create', compact('teamTypes', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(Request $request)
    {
        if(Auth::user()->ownTeams()->count() >= 3) {
            flash()->error('Вы можете создать не больше 3 комманды!');

            return redirect()->back();
        }

        $rules = [
            'name' => 'required|max:200',
            'team_type_id' => 'required',
            'slug' => 'required|unique:teams',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,gif',
        ];

        $attributes = $request->all();

        $attributes['slug'] = str_slug($request->get('name', ''));

        $validator = Validator::make($attributes, $rules, ['unique' => 'Название не уникально.']);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $attributes['user_id'] = auth()->user()->id;

        $team = Team::create($attributes);

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $team->addLogo($request->file('logo'));
        }

        TeamUsers::create([
            'team_id' => $team->id,
            'user_id' => auth()->id(),
            'position' => 'Создатель',
            'is_approved' => true
        ]);

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
        if (!$this->userIsTeamAdmin($team)) {
            flash()->error('Access denied!');

            return redirect()->back();
        }

        $teamTypes = TeamType::all();

        $connections = TeamUsers::where('team_id', $team->id)->get();

        $users = User::where('id', '!=', auth()->id())->get();

        return view('teams.edit', compact('team', 'teamTypes', 'connections', 'users'));
    }

    /**
     * Update a resource in storage.
     *
     * @param Request $request
     * @param Team $team
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Exception
     */
    public function update(Request $request, Team $team)
    {
        if (!$this->userIsTeamAdmin($team)) {
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
        $teamIds = Auth::user()
            ->teams()
            ->pluck('team_id')
            ->toArray();

        $followerTeamIds = $this->getFollowerTeamIds();

        $teams = Team::where('user_id', auth()->id())
            ->orWhereIn('id', array_merge($teamIds, $followerTeamIds))
            ->get();

        $teamProjects = [];
        $followerProjects = [];

        foreach ($teams as $team) {
            $projects = Project::where('team_id', $team->id)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();

            if(in_array($team->id, $followerTeamIds)) {
                $projects = $projects->filter(function ($project) {
                    return ProjectUsers::where('project_id', $project->id)
                        ->where('user_id', Auth::id())
                        ->where('user_role', 'follower')
                        ->count();
                });

                $followerProjects[$team->id] = $projects;
            } else {
                $teamProjects[$team->id] = $projects;
            }
        }

        $teamSelected = request('team_id', ($teams->first() ? $teams->first()->id : 0));


        return view('teams.projects', compact('projects', 'teams', 'teamProjects', 'teamSelected', 'followerProjects'));
    }

    private function getFollowerTeamIds()
    {
        $followerProjectIds = ProjectUsers::where('user_id', Auth::id())
            ->where('user_role', 'follower')
            ->pluck('project_id')
            ->toArray();

        return Project::whereIn('id', $followerProjectIds)
            ->whereNotNull('team_id')
            ->pluck('team_id')
            ->toArray();
    }

    /**
     * Update a resource in storage.
     *
     * @param Team $team
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function favorite(Team $team)
    {
        $team->setFavorited();

        flash()->success('Команда добавлена в избранные!');

        return redirect()->back();
    }

    /**
     * Update a resource in storage.
     *
     * @param Team $team
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function unfavorite(Team $team)
    {
        $team->setUnfavorited();

        flash()->success('Команда удалена с избранных!');

        return redirect()->back();
    }

    /**
     * Update a resource in storage.
     *
     * @param Team $team
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function addAdmin(Team $team)
    {
        if (auth()->id() != $team->user_id) {
            flash()->error('Access denied!');

            return redirect()->back();
        }

        $connection = TeamUsers::where('team_id', $team->id)
            ->where('user_id', request('user_id'))
            ->first();

        if (!$connection) {
            flash()->error('Access denied!');

            return redirect(route('teams.show', $team));
        }

        TeamUsers::where('team_id', $team->id)
            ->where('user_id', request('user_id'))
            ->update(['is_admin' => true]);

        flash()->success('Доступ открыт.');

        return redirect(route('teams.show', $team));
    }

    /**
     * Update a resource in storage.
     *
     * @param Team $team
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function deleteAdmin(Team $team)
    {
        if (auth()->id() != $team->user_id) {
            flash()->error('Access denied!');

            return redirect()->back();
        }

        $connection = TeamUsers::where('team_id', $team->id)
            ->where('user_id', request('user_id'))
            ->first();

        if (!$connection) {
            flash()->error('Access denied!');

            return redirect(route('teams.show', $team));
        }

        TeamUsers::where('team_id', $team->id)
            ->where('user_id', request('user_id'))
            ->update(['is_admin' => false]);

        flash()->success('Доступ закрыт.');

        return redirect(route('teams.show', $team));
    }

    /**
     * Destroy a resource in storage.
     *
     * @param Team $team
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Exception
     */
    public function destroy(Team $team)
    {
        if (!$this->userIsTeamAdmin($team)) {
            flash()->error('Access denied!');

            return redirect()->back();
        }

        TeamUsers::where('team_id', $team->id)
            ->delete();

        $team->delete();

        flash()->success('Команда удалена.');

        return redirect(route('teams.index'));
    }

    /**
     * Update a resource in storage.
     *
     * @param Team $team
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Exception
     */
    public function disconnect(Team $team)
    {
        if (auth()->id() == $team->user_id || !$this->userIsConnected($team)) {
            flash()->error('Access denied!');

            return redirect()->back();
        }

        TeamUsers::where('team_id', $team->id)
            ->where('user_id', auth()->id())
            ->delete();

        flash()->success('Вы успешно покинули команду.');

        return redirect(route('teams.myteams'));
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
            $connectionIds = TeamUsers::where('team_id', $team->id)
                ->pluck('position', 'user_id')
                ->toArray();

            foreach ($request->get('connections') as $user_id => $connection) {
                if (isset($connectionIds[$user_id])) {
                    if ($connectionIds[$user_id] != $connection['position']) {
                        TeamUsers::where('team_id', $team->id)
                            ->where('user_id', $user_id)
                            ->update(['position' => $connection['position']]);
                    }

                    unset($connectionIds[$user_id]);
                } else {
                    if($user_id != auth()->id()) {
                        $teamUser = TeamUsers::create([
                            'team_id' => $team->id,
                            'user_id' => $user_id,
                            'position' => $connection['position']
                        ]);

                        if ($recipient = User::find($user_id)) {
                            $recipient->notify(new TeamUserNotification($teamUser));
                        }
                    }
                }
            }

            foreach ($connectionIds as $user_id => $position) {
                TeamUsers::where('team_id', $team->id)
                    ->where('user_id', $user_id)
                    ->where('user_id', '!=', auth()->id())
                    ->delete();
            }
        } else {
            TeamUsers::where('team_id', $team->id)
                ->where('user_id', '!=', auth()->id())
                ->delete();
        }
    }

    private function userIsTeamAdmin($team)
    {
        if (auth()->id() == $team->user_id) {
            return true;
        }

        if ($connection = TeamUsers::where('team_id', $team->id)
            ->where('user_id', auth()->id())
            ->where('is_admin', true)
            ->first()) {

            return true;
        }

        return false;
    }

    private function userIsConnected($team)
    {
        if (auth()->id() == $team->user_id) {
            return false;
        }

        if ($connection = TeamUsers::where('team_id', $team->id)
            ->where('user_id', auth()->id())
            ->first()) {

            return true;
        }

        return false;
    }
}
