<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Jobs\Job;
use App\Models\Project;
use Illuminate\Http\Request;

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
        return view('projects.index', ['projects' => auth()->user()->projects]);
    }

    /**
     * Display the specified resource.
     *
     * @param Project $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
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

        return view('projects.create', compact('icons'));
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

        return redirect(route('projects.index'));
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

        return view('projects.edit', compact('project', 'icons'));
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

        return redirect(route('projects.index'));
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

        if(request('redirect') == 'my-bookmarks#projects') {
            return redirect(route('my-bookmarks') . '#projects');
        } else {
            return redirect(route('projects.index'));
        }
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
            $id_ary = explode(",", $request ->sort_order);

            for ($i = 0; $i < count($id_ary); $i++) {
                $q = Project::find($id_ary[$i]);

                if($q) {
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
            $id_ary = explode(",", $request ->sort_order);

            for ($i = 0; $i < count($id_ary); $i++) {
                $q = Job::find($id_ary[$i]);

                if($q) {
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

        if(request('redirect') == 'my-bookmarks#projects') {
            return redirect(route('my-bookmarks') . '#projects');
        } else {
            return redirect(route('projects.index'));
        }
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

        if(request('redirect') == 'my-bookmarks#projects') {
            return redirect(route('my-bookmarks') . '#projects');
        } else {
            return redirect(route('projects.index'));
        }
    }

    /**
     * Update a resource in storage.
     *
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function favorite(Project $project)
    {
        $project->is_favorite = true;

        $project->save();

        flash()->success('Project updated!');

        if(request('redirect') == 'my-bookmarks#projects') {
            return redirect(route('my-bookmarks') . '#projects');
        } else {
            return redirect(route('projects.index'));
        }
    }

    /**
     * Update a resource in storage.
     *
     * @param Project $project
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function unfavorite(Project $project)
    {
        $project->is_favorite = false;

        $project->save();

        flash()->success('Project updated!');

        if(request('redirect') == 'my-bookmarks#projects') {
            return redirect(route('my-bookmarks') . '#projects');
        } else {
            return redirect(route('projects.index'));
        }
    }
}