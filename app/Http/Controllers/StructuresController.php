<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Project;
use App\Models\Structure;
use App\Models\StructureType;
use App\Models\StructureUsers;
use App\Notifications\StructureUserNotification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StructuresController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Organization $organization
     * @return \Illuminate\Http\Response
     */
    public function index(Organization $organization)
    {
        $connections = [];

        return view('structures.index', compact('organization', 'connections'));
    }

    /**
     * Display the specified resource.
     *
     * @param Structure $structure
     * @return \Illuminate\Http\Response
     */
    public function show(Structure $structure)
    {
        $userIsAdmin = $this->userIsStructureAdmin($structure);

        $userIsConnected = $this->userIsConnected($structure);

        $connections = StructureUsers::where('structure_id', $structure->id)->get();

        return view('structures.show', compact('structure', 'connections', 'userIsAdmin', 'userIsConnected'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Organization $organization
     * @return \Illuminate\Http\Response
     */
    public function create(Organization $organization)
    {
        $users = User::where('id', '!=', auth()->id())->get();

        return view('structures.create', compact('organization', 'users'));
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
        $rules = [
            'name' => 'required|max:200',
            'structure_type_id' => 'required',
            'slug' => 'required|unique:structures',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,gif',
        ];

        $attributes = $request->all();

        $attributes['slug'] = str_slug($request->get('name', ''));

        $validator = Validator::make($attributes, $rules, ['unique' => 'Название не уникально.']);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $attributes['user_id'] = auth()->user()->id;

        $structure = Structure::create($attributes);

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $structure->addLogo($request->file('logo'));
        }

        StructureUsers::create([
            'structure_id' => $structure->id,
            'user_id' => auth()->id(),
            'position' => 'Создатель',
            'is_approved' => true
        ]);

        $this->addConnection($request, $structure);

        flash()->success('Structure saved!');

        return redirect(route('structures.show', $structure));
    }

    /**
     * Show the form for editing a resource.
     *
     * @param Structure $structure
     * @return \Illuminate\Http\Response
     */
    public function edit(Structure $structure)
    {
        if (!$this->userIsStructureAdmin($structure)) {
            flash()->error('Access denied!');

            return redirect()->back();
        }

        $structureTypes = StructureType::all();

        $connections = StructureUsers::where('structure_id', $structure->id)->get();

        $users = User::where('id', '!=', auth()->id())->get();

        return view('structures.edit', compact('structure', 'structureTypes', 'connections', 'users'));
    }

    /**
     * Update a resource in storage.
     *
     * @param Request $request
     * @param Structure $structure
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Exception
     */
    public function update(Request $request, Structure $structure)
    {
        if (!$this->userIsStructureAdmin($structure)) {
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

        $structure->description = $request->get('description', '');
        $structure->save();

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $structure->addLogo($request->file('logo'));
        }

        $this->addConnection($request, $structure);

        flash()->success('Structure updated!');

        return redirect(route('structures.show', $structure));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function projects()
    {
        $structureIds = auth()->user()->structures()->pluck('structure_id')->toArray();

        $structures = Structure::where('user_id', auth()->id())
            ->orWhereIn('id', $structureIds)
            ->get();

        $structureProjects = [];

        foreach ($structures as $structure) {
            $projects = Project::where('structure_id', $structure->id)->orderBy('sort_order')->orderBy('name')->get();

            $structureProjects[$structure->id] = $projects;
        }

        $structureSelected = request('structure_id', ($structures->first() ? $structures->first()->id : 0));

        return view('structures.projects', compact('projects', 'structures', 'structureProjects', 'structureSelected'));
    }

    /**
     * Destroy a resource in storage.
     *
     * @param Structure $structure
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Exception
     */
    public function destroy(Structure $structure)
    {
        if (!$this->userIsStructureAdmin($structure)) {
            flash()->error('Access denied!');

            return redirect()->back();
        }

        StructureUsers::where('structure_id', $structure->id)
            ->delete();

        $structure->delete();

        flash()->success('Команда удалена.');

        return redirect(route('structures.index'));
    }

    /**
     * Update a resource in storage.
     *
     * @param Structure $structure
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Exception
     */
    public function disconnect(Structure $structure)
    {
        if (auth()->id() == $structure->user_id || !$this->userIsConnected($structure)) {
            flash()->error('Access denied!');

            return redirect()->back();
        }

        StructureUsers::where('structure_id', $structure->id)
            ->where('user_id', auth()->id())
            ->delete();

        flash()->success('Вы успешно покинули команду.');

        return redirect(route('structures.mystructures'));
    }

    /**
     * Add connections.
     *
     * @param Request $request
     * @param Structure $structure
     * @throws \Exception
     */
    protected function addConnection(Request $request, Structure $structure)
    {
        if ($request->has('connections')) {
            $connectionIds = StructureUsers::where('structure_id', $structure->id)
                ->pluck('position', 'user_id')
                ->toArray();

            foreach ($request->get('connections') as $user_id => $connection) {
                if (isset($connectionIds[$user_id])) {
                    if ($connectionIds[$user_id] != $connection['position']) {
                        StructureUsers::where('structure_id', $structure->id)
                            ->where('user_id', $user_id)
                            ->update(['position' => $connection['position']]);
                    }

                    unset($connectionIds[$user_id]);
                } else {
                    if($user_id != auth()->id()) {
                        $structureUser = StructureUsers::create([
                            'structure_id' => $structure->id,
                            'user_id' => $user_id,
                            'position' => $connection['position']
                        ]);

                        if ($recipient = User::find($user_id)) {
                            $recipient->notify(new StructureUserNotification($structureUser));
                        }
                    }
                }
            }

            foreach ($connectionIds as $user_id => $position) {
                StructureUsers::where('structure_id', $structure->id)
                    ->where('user_id', $user_id)
                    ->where('user_id', '!=', auth()->id())
                    ->delete();
            }
        } else {
            StructureUsers::where('structure_id', $structure->id)
                ->where('user_id', '!=', auth()->id())
                ->delete();
        }
    }

    private function userIsStructureAdmin($structure)
    {
        if (auth()->id() == $structure->user_id) {
            return true;
        }

        if ($connection = StructureUsers::where('structure_id', $structure->id)
            ->where('user_id', auth()->id())
            ->where('is_admin', true)
            ->first()) {

            return true;
        }

        return false;
    }

    private function userIsConnected($structure)
    {
        if (auth()->id() == $structure->user_id) {
            return false;
        }

        if ($connection = StructureUsers::where('structure_id', $structure->id)
            ->where('user_id', auth()->id())
            ->first()) {

            return true;
        }

        return false;
    }
}
