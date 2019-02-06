<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Jobs\Job;
use App\Models\Organization;
use App\Models\OrganizationUsers;
use App\Models\Project;
use App\Models\ProjectUsers;
use App\Models\Structure;
use App\Models\StructureUsers;
use App\Models\Team;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Transformers;
use Illuminate\Support\Facades\Auth;

class ProjectsController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('project.owner')->only(['show', 'edit', 'update', 'destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = auth()->user()->projects()->where('team_id', null)->get();

        return view('projects.index', compact('projects'));
    }

    /**
     * Display the specified resource.
     *
     * @param Project $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        if ($project->user_id != auth()->id() && !$project->structure_id) {
            if (!$project->team_id) {
                flash()->error('Access denied!');

                return $this->getRedirectRoute();
            }

            $team = auth()->user()->allUserTeams()
                ->where('id', $project->team_id)
                ->get();

            if (!$team) {
                flash()->error('Access denied!');

                return $this->getRedirectRoute();
            }
        }

        $totalPrice = $this->getTotalPrice($project);

        return view('projects.show', compact('project', 'totalPrice'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function create()
    {
        $icons = config('project.icons');

        $teams = auth()->user()->allUserTeams()->get();

        $organizations = $this->getOrganizations();

        $structures = $this->getStructures();

        $team_id = request('team_id');

        $structure_id = request('structure_id');

        $users = $this->getProjectUsers($structure_id);

        $allFollowers = $this->getProjectFollowers($team_id);

        return view('projects.create', compact('icons', 'teams', 'team_id', 'organizations', 'structures', 'structure_id', 'users', 'allFollowers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProjectRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(ProjectRequest $request)
    {
        if (!$request->get('description')) {
            $request->request->set('description', '');
        }

        if ($request->get('team_id') == 'organization') {
            $request->request->set('team_id', null);
            $request->request->set('project_type', 'organization');
        } elseif ($request->get('team_id')) {
            $request->request->set('project_type', 'team');
        } else {
            $request->request->set('project_type', 'personal');
        }

        if ($request->get('deadline_at')) {
            $request->merge([
                'deadline_at' => Carbon::createFromFormat('d.m.Y H:i', $request->get('deadline_at'))->format('Y-m-d H:i:s')
            ]);
        }

        $project = auth()->user()->addProject($request->except('followers'));

        $this->addConnection($request, $project, 'employer');
        $this->addConnection($request, $project, 'follower');

        flash()->success('Project saved!');

        return $this->getRedirectRoute();
    }

    /**
     * Show the form for editing a resource.
     *
     * @param Project $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $icons = config('project.icons');

        $teams = auth()->user()->allUserTeams()->get();

        $organizations = $this->getOrganizations();

        $structures = $this->getStructures();

        $connections = ProjectUsers::where('project_id', $project->id)
            ->where('user_role', 'employer')
            ->get();

        $followers = ProjectUsers::where('project_id', $project->id)
            ->where('user_role', 'follower')
            ->get();

        $users = $this->getProjectUsers($project->structure_id);

        $allFollowers = $this->getProjectFollowers($project->team_id);

        return view('projects.edit', compact('project', 'icons', 'teams', 'organizations', 'structures', 'users', 'connections', 'allFollowers', 'followers'));
    }

    /**
     * Update a resource in storage.
     *
     * @param ProjectRequest $request
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Exception
     */
    public function update(ProjectRequest $request, Project $project)
    {
        if (!$request->get('description')) {
            $request->request->set('description', '');
        }

        if ($request->get('team_id') == 'organization') {
            $request->request->set('team_id', null);
            $request->request->set('project_type', 'organization');
        } elseif ($request->get('team_id')) {
            $request->request->set('project_type', 'team');
        } else {
            $request->request->set('project_type', 'personal');
        }

        if ($request->get('deadline_at')) {
            $request->merge([
                'deadline_at' => Carbon::createFromFormat('d.m.Y H:i', $request->get('deadline_at'))->format('Y-m-d H:i:s')
            ]);
        }

        $project->update($request->except('followers'));

        $this->addConnection($request, $project, 'employer');
        $this->addConnection($request, $project, 'follower');

        flash()->success('Project updated!');

        return $this->getRedirectRoute();
    }

    /**
     * Destroy a resource in storage.
     *
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Exception
     */
    public function destroy(Project $project)
    {
        $this->updateJobs($project);

        $project->delete();

        flash()->success('Project deleted!');

        return $this->getRedirectRoute();
    }

    /**
     * @param Project $project
     */
    protected function updateJobs(Project $project)
    {
        Job::where('project_id', $project->id)->update(['project_id' => null]);
    }

    /**
     * @param Request $request
     * @return string
     */
    function order(Request $request)
    {
        if ($request->ajax()) {
            $id_ary = explode(",", $request->sort_order);

            for ($i = 0; $i < count($id_ary); $i++) {
                $q = Project::find($id_ary[$i]);

                if ($q) {
                    $q->sort_order = $i;
                    $q->save();
                }
            }

            return 'success';
        }

        return '';
    }

    /**
     * @param Request $request
     * @return string
     */
    function orderJobs(Request $request)
    {
        if ($request->ajax()) {
            $id_ary = explode(",", $request->sort_order);

            for ($i = 0; $i < count($id_ary); $i++) {
                $q = Job::find($id_ary[$i]);

                if ($q) {
                    $q->sort_order_for_project = $i;
                    $q->save();
                }
            }

            return 'success';
        }

        return '';
    }

    /**
     * Update a resource in storage.
     *
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function done(Project $project)
    {
        $project->is_archived = true;

        $project->save();

        flash()->success('Project archived!');

        return $this->getRedirectRoute();
    }

    /**
     * Update a resource in storage.
     *
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function restore(Project $project)
    {
        $project->is_archived = false;

        $project->save();

        flash()->success('Project restored!');

        return $this->getRedirectRoute();
    }

    /**
     * Update a resource in storage.
     *
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function favorite(Project $project)
    {
        $project->setFavorited();

        flash()->success('Project updated!');

        return $this->getRedirectRoute();
    }

    /**
     * Update a resource in storage.
     *
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function unfavorite(Project $project)
    {
        $project->setUnfavorited();

        flash()->success('Project updated!');

        return $this->getRedirectRoute();
    }

    protected function getRedirectRoute()
    {
        if (request('redirect') == 'my-bookmarks#projects') {
            return redirect(route('my-bookmarks') . '#projects');
        } elseif (request('team_id') && request('team_id') != 'organization') {
            return redirect(route('teams.projects') . '#team-' . (int)request('team_id'));
        } elseif (request('structure_id') && $structure = Structure::find(request('structure_id'))) {
            return redirect(route('structure.show', [$structure->organization, $structure]));
        } else {
            return redirect(route('projects.index'));
        }
    }

    protected function getTotalPrice(Project $project)
    {
        $totalPrice = $project->jobs()->sum('price');

        if (currency()->getUserCurrency() != currency()->config('default')) {
            $totalPrice = currency($totalPrice, currency()->config('default'), currency()->getUserCurrency(), false);
        }

        return currency_format($totalPrice, currency()->getUserCurrency());
    }

    private function getOrganizations()
    {
        $organizationIds1 = Organization::my()->pluck('id')->toArray();
        $organizationIds2 = OrganizationUsers::where('user_id', Auth::id())->pluck('organization_id')->toArray();

        $organizationIds = array_merge($organizationIds1, $organizationIds2);

        return Organization::whereIn('id', $organizationIds)->get();
    }

    private function getStructures()
    {
        $organizationIdsOwner = Organization::my()
            ->pluck('id')
            ->toArray();

        $structureIdsOwner = Structure::whereIn('organization_id', $organizationIdsOwner)
            ->pluck('id')
            ->toArray();

        $structureIdsAccess = StructureUsers::where('user_id', Auth::id())
            ->where('can_add_project', true)
            ->pluck('structure_id')
            ->toArray();

        $structureIds = array_merge($structureIdsOwner, $structureIdsAccess);

        return Structure::whereIn('id', $structureIds)->get();
    }

    /**
     * Add connections.
     *
     * @param Request $request
     * @param Project $project
     * @param string $role
     * @throws \Exception
     */
    protected function addConnection(Request $request, Project $project, $role = 'employer')
    {
        $field = $role == 'employer' ? 'connections' : 'followers';

        if ($request->has($field)) {
            $connectionIds = ProjectUsers::where('project_id', $project->id)
                ->where('user_role', $role)
                ->pluck('project_id', 'user_id')
                ->toArray();

            foreach ($request->get($field) as $user_id => $connection) {
                if (isset($connectionIds[$user_id])) {
                    unset($connectionIds[$user_id]);
                } else {
                    ProjectUsers::create([
                        'user_role' => $role,
                        'project_id' => $project->id,
                        'user_id' => $user_id
                    ]);
                }
            }

            foreach ($connectionIds as $user_id => $position) {
                ProjectUsers::where('project_id', $project->id)
                    ->where('user_role', $role)
                    ->where('user_id', $user_id)
                    ->delete();
            }
        } else {
            ProjectUsers::where('project_id', $project->id)
                ->where('user_role', $role)
                ->delete();
        }
    }

    /**
     * @param $structure_id
     * @return User[]|null
     */
    private function getProjectUsers($structure_id)
    {
        if (!$structure_id || !$structure = Structure::find($structure_id)) {
            return null;
        }

        if (Auth::user()->isOrganizationFullAccess($structure->organization)) {
            $userIds = $this->getProjectUsersId($structure_id);

            return User::whereIn('id', $userIds)->get();
        }

        $connection = StructureUsers::where('structure_id', $structure_id)
            ->where('user_id', Auth::id())
            ->first();

        if ($connection && $connection->can_add_user_to_project) {
            $userIds = $this->getProjectUsersId($structure_id);

            return User::whereIn('id', $userIds)->get();
        }

        return null;
    }

    /**
     * @param $structure_id
     * @return array
     */
    private function getProjectUsersId($structure_id)
    {
        return StructureUsers::where('structure_id', $structure_id)
            ->where('is_approved', true)
            ->pluck('user_id')
            ->toArray();
    }

    /**
     * @param $team_id
     * @return User[]|null
     */
    private function getProjectFollowers($team_id)
    {
        $team = Team::find($team_id);

        if (!$team || !Auth::user()->isTeamAdmin($team)) {
            return null;
        }

        $userIds = $this->getProjectFollowersId($team);

        return User::whereIn('id', $userIds)
            ->where('id', '<>', Auth::id())
            ->get();
    }

    /**
     * @param Team $team
     * @return array
     */
    private function getProjectFollowersId(Team $team)
    {
        $userIds = [];

        foreach ($team->projects as $project) {
            $userIds = array_merge(
                $userIds,
                $project->jobs()
                    ->pluck('user_id')
                    ->toArray()
            );
        }

        return $userIds;
    }
}