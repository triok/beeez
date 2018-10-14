<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Jobs\Job;
use App\Models\Project;
use App\Models\Team;
use Illuminate\Http\Request;
use App\Transformers;

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

            if(!$team) {
                flash()->error('Access denied!');

                return $this->getRedirectRoute();
            }
        }

        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $icons = config('project.icons');

        $teams = auth()->user()->allUserTeams()->get();

        $team_id = request('team_id');

        return view('projects.create', compact('icons', 'teams', 'team_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ProjectRequest $request)
    {
        auth()->user()->addProject($request->all());

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

        return view('projects.edit', compact('project', 'icons', 'teams'));
    }

    /**
     * Update a resource in storage.
     *
     * @param Request $request
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function update(ProjectRequest $request, Project $project)
    {
        $project->update($request->all());

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
        } elseif (request('team_id')) {
            return redirect(route('teams.projects') . '#team-' . (int)request('team_id'));
        } else {
            return redirect(route('projects.index'));
        }
    }
}