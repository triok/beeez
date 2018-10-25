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
        $this->middleware('organization.owner')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Organization $organization
     * @return \Illuminate\Http\Response
     */
    public function index(Organization $organization)
    {
        // todo: ...
        $connections = [];

        $structures = $organization->structures;

        return view('structures.index', compact('organization', 'structures', 'connections'));
    }

    /**
     * Display the specified resource.
     *
     * @param Structure $structure
     * @return \Illuminate\Http\Response
     */
    public function show(Structure $structure)
    {
//        $userIsAdmin = $this->userIsStructureAdmin($structure);
//
//        $userIsConnected = $this->userIsConnected($structure);
//
//        $connections = StructureUsers::where('structure_id', $structure->id)->get();
//
//        return view('structures.show', compact('structure', 'connections', 'userIsAdmin', 'userIsConnected'));
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
     * @param Organization $organization
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function store(Request $request, Organization $organization)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:200',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $structure = $organization->structures()->create([
            'name' => $request->get('name'),
            'description' => $request->get('description')
        ]);

        $this->addConnection($request, $structure);

        flash()->success('Отдел создан!');

        return redirect(route('structure.index', $organization));
    }

    /**
     * Show the form for editing a resource.
     *
     * @param Organization $organization
     * @param Structure $structure
     * @return \Illuminate\Http\Response
     */
    public function edit(Organization $organization, Structure $structure)
    {
        $connections = StructureUsers::where('structure_id', $structure->id)->get();

        $users = User::where('id', '!=', auth()->id())->get();

        return view('structures.edit', compact('organization', 'structure', 'connections', 'users'));
    }

    /**
     * Update a resource in storage.
     *
     * @param Request $request
     * @param Organization $organization
     * @param Structure $structure
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Exception
     */
    public function update(Request $request, Organization $organization, Structure $structure)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:200',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $structure->update([
            'name' => $request->get('name'),
            'description' => $request->get('description')
        ]);

        $this->addConnection($request, $structure);

        flash()->success('Отдел обновлен!');

        return redirect(route('structure.index', $organization));
    }

    /**
     * Destroy a resource in storage.
     *
     * @param Organization $organization
     * @param Structure $structure
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Exception
     */
    public function destroy(Organization $organization, Structure $structure)
    {
        StructureUsers::where('structure_id', $structure->id)->delete();

        $structure->delete();

        flash()->success('Отдел удалено.');

        return redirect(route('structure.index', $organization));
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
                    StructureUsers::create([
                        'structure_id' => $structure->id,
                        'user_id' => $user_id,
                        'position' => $connection['position']
                    ]);
                }
            }

            foreach ($connectionIds as $user_id => $position) {
                StructureUsers::where('structure_id', $structure->id)
                    ->where('user_id', $user_id)
                    ->delete();
            }
        } else {
            StructureUsers::where('structure_id', $structure->id)->delete();
        }
    }
}
