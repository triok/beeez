<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\OrganizationUsers;
use App\Models\Project;
use App\Models\Structure;
use App\Models\StructureType;
use App\Models\StructureUsers;
use App\Notifications\StructureUserNotification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StructuresController extends Controller
{
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
     * @param Organization $organization
     * @param Structure $structure
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization, Structure $structure)
    {
        $connections = StructureUsers::where('structure_id', $structure->id)->get();

        return view('structures.show', compact('organization', 'structure', 'connections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Organization $organization
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function create(Organization $organization)
    {
        $this->authorize('updateStructure', $organization);

        $users = $organization->users()->get();

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
        $this->authorize('updateStructure', $organization);

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
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Organization $organization, Structure $structure)
    {
        $this->authorize('updateStructure', $organization);

        $connections = StructureUsers::where('structure_id', $structure->id)->get();
        $users = $organization->users()->get();

        // $users = User::where('id', '!=', auth()->id())->get();

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
        $this->authorize('updateStructure', $organization);

        if(Auth::user()->isOrganizationAdmin($organization)) {
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

            $this->addConnection($request, $structure, true);
        } else {
            $this->addConnection($request, $structure, false);
        }

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
        $this->authorize('updateStructure', $organization);

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
    protected function addConnection(Request $request, Structure $structure, $isAdmin = true)
    {
        if ($request->has('connections')) {
            $connectionIds = StructureUsers::where('structure_id', $structure->id)
                ->pluck('user_id')
                ->toArray();

            foreach ($request->get('connections') as $user_id => $connection) {
                if (isset($connectionIds[$user_id])) {
                    if($isAdmin) {
                        StructureUsers::where('structure_id', $structure->id)
                            ->where('user_id', $user_id)
                            ->update([
                                'can_add_user' => isset($connection['can_add_user']),
                                'can_add_project' => isset($connection['can_add_project']),
                                'can_add_job' => isset($connection['can_add_job']),
                                'can_see_all_projects' => isset($connection['can_see_all_projects']),
                                'can_add_user_to_project' => isset($connection['can_add_user_to_project'])
                            ]);
                    } else {
                        StructureUsers::where('structure_id', $structure->id)
                            ->where('user_id', $user_id);
                    }

                    unset($connectionIds[$user_id]);
                } else {
                    if($isAdmin) {
                        $structureUsers = StructureUsers::create([
                            'structure_id' => $structure->id,
                            'can_add_user' => isset($connection['can_add_user']),
                            'can_add_project' => isset($connection['can_add_project']),
                            'can_add_job' => isset($connection['can_add_job']),
                            'can_see_all_projects' => isset($connection['can_see_all_projects']),
                            'can_add_user_to_project' => isset($connection['can_add_user_to_project'])
                        ]);

                        if ($recipient = User::find($user_id)) {
                            $recipient->notify(new StructureUserNotification($structureUsers));
                        }
                    } else {
                        $structureUsers = StructureUsers::create([
                            'structure_id' => $structure->id,
                            'user_id' => $user_id,
                            
                        ]);

                        if ($recipient = User::find($user_id)) {
                            $recipient->notify(new StructureUserNotification($structureUsers));
                        }
                    }
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
