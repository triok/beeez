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
        $this->middleware('project.owner')->only(['edit', 'update', 'destroy']);
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
        if ($project->user_id != auth()->id()) {
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

        $team_id = request('team_id');

        $structure_id = request('structure_id');

        $users = [];

        if($structure = Structure::find($structure_id)) {
            $userIds = StructureUsers::where('structure_id', $structure_id)->pluck('user_id')->toArray();

            $users = User::whereIn('id', $userIds)->get();
        }

        return view('projects.create', compact('icons', 'teams', 'team_id', 'organizations', 'structure_id', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProjectRequest $request
     * @return \Illuminate\Http\RedirectResponse
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

        $project = auth()->user()->addProject($request->all());

        $this->addConnection($request, $project);

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

        $connections = ProjectUsers::where('project_id', $project->id)->get();

        $users = [];

        if($structure = Structure::find($project->structure_id)) {
            $userIds = StructureUsers::where('structure_id', $project->structure_id)->pluck('user_id')->toArray();

            $users = User::whereIn('id', $userIds)->get();
        }

        return view('projects.edit', compact('project', 'icons', 'teams', 'organizations', 'users', 'connections'));
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

        $project->update($request->all());

        $this->addConnection($request, $project);

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

    /**
     * Add connections.
     *
     * @param Request $request
     * @param Project $project
     * @throws \Exception
     */
    protected function addConnection(Request $request, Project $project)
    {
        if ($request->has('connections')) {
            $connectionIds = ProjectUsers::where('project_id', $project->id)
                ->pluck('project_id', 'user_id')
                ->toArray();

            foreach ($request->get('connections') as $user_id => $connection) {
                if (isset($connectionIds[$user_id])) {
                    unset($connectionIds[$user_id]);
                } else {
                    ProjectUsers::create([
                        'project_id' => $project->id,
                        'user_id' => $user_id
                    ]);
                }
            }

            foreach ($connectionIds as $user_id => $position) {
                ProjectUsers::where('project_id', $project->id)
                    ->where('user_id', $user_id)
                    ->delete();
            }
        } else {
            ProjectUsers::where('structure_id', $project->id)->delete();
        }
    }
}