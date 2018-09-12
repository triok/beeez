<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\OrganizationUsers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrganizationsController extends Controller
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
        // ID организаций в которых состоит пользователь
        $otherOrganizationIds = OrganizationUsers::where('user_id', '=', auth()->id())
            ->pluck('organization_id')
            ->toArray();

        $organizations = Organization::where('user_id', auth()->id())
            ->orWhere(function ($query) use ($otherOrganizationIds) {
                $query
                    ->whereIn('id', $otherOrganizationIds)
                    ->where('is_approved', true);
            })
            ->paginate(request('count', 20));

        return view('organizations.index', compact('organizations'));
    }

    /**
     * Display the specified resource.
     *
     * @param Organization $organization
     * @return \Illuminate\Http\Response
     */
    public function show(Organization $organization)
    {
        $connections = OrganizationUsers::where('organization_id', $organization->id)->get();

        return view('organizations.show', compact('organization', 'connections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();

        return view('organizations.create', compact('users'));
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
            'slug' => 'required|unique:organizations',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,gif',
        ];

        $attributes = $request->all();

        $attributes['slug'] = str_slug($request->get('name', ''));

        $validator = Validator::make($attributes, $rules, ['unique' => 'The name has already been taken.']);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $attributes['user_id'] = auth()->user()->id;

        $organization = Organization::create($attributes);

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $organization->addLogo($request->file('logo'));
        }

        $this->addConnection($request, $organization);

        flash()->success('Organization saved!');

        return redirect(route('organizations.show', $organization));
    }

    /**
     * Show the form for editing a resource.
     *
     * @param Organization $organization
     * @return \Illuminate\Http\Response
     */
    public function edit(Organization $organization)
    {
        if ($organization->user_id != auth()->user()->id) {
            flash()->error('Access denied!');

            return redirect()->back();
        }

        $connections = OrganizationUsers::where('organization_id', $organization->id)->get();

        $users = User::all();

        return view('organizations.edit', compact('organization', 'connections', 'users'));
    }

    /**
     * Update a resource in storage.
     *
     * @param Request $request
     * @param Organization $organization
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function update(Request $request, Organization $organization)
    {
        if ($organization->user_id != auth()->user()->id) {
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

        $organization->description = $request->get('description', '');
        $organization->save();

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $organization->addLogo($request->file('logo'));
        }

        $this->addConnection($request, $organization);

        flash()->success('Organization updated!');

        return redirect(route('organizations.show', $organization));
    }

    /**
     * Add connections.
     *
     * @param Request $request
     * @param Organization $organization
     * @throws \Exception
     */
    protected function addConnection(Request $request, Organization $organization)
    {
        if ($request->has('connections')) {
            $connectionIds = OrganizationUsers::where('organization_id', $organization->id)->pluck('position', 'user_id')->toArray();

            foreach ($request->get('connections') as $user_id => $connection) {
                if (isset($connectionIds[$user_id])) {
                    if ($connectionIds[$user_id] != $connection['position']) {
                        OrganizationUsers::where('organization_id', $organization->id)
                            ->where('user_id', $user_id)
                            ->update(['position' => $connection['position']]);
                    }

                    unset($connectionIds[$user_id]);
                } else {
                    OrganizationUsers::create([
                        'organization_id' => $organization->id,
                        'user_id' => $user_id,
                        'position' => $connection['position']
                    ]);
                }
            }

            foreach ($connectionIds as $user_id => $position) {
                OrganizationUsers::where('organization_id', $organization->id)
                    ->where('user_id', $user_id)
                    ->delete();
            }
        } else {
            OrganizationUsers::where('organization_id', $organization->id)
                ->delete();
        }
    }
}
