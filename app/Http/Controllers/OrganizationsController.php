<?php

namespace App\Http\Controllers;

use App\Mail\NewOrganization;
use App\Models\Organization;
use App\Models\OrganizationUsers;
use App\Notifications\OrganizationNotification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class OrganizationsController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('organization.owner')->only(['edit', 'update', 'destroy']);
        $this->middleware('organization.admin')->only(['moderation', 'approve', 'reject']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $organizations = Organization::approved()->paginate(request('count', 20));

        return view('organizations.index', compact('organizations'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function my()
    {
        $organizations = Organization::my()->paginate(request('count', 20));

        return view('organizations.my-organizations', compact('organizations'));
    }

    public function moderation()
    {
        $organizations = Organization::moderation()->paginate(request('count', 20));

        return view('organizations.moderation', compact('organizations'));
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

    public function structure(Organization $organization)
    {
        $connections = OrganizationUsers::where('organization_id', $organization->id)->get();

        return view('organizations.structure', compact('organization', 'connections'));
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
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $rules = [
            'name'           => 'required|max:200',
            'ownership'      => 'required|max:200',
            'ohrn'           => 'required|digits_between:13,15|integer|unique:organizations',
            'inn'            => 'required|digits_between:10,12|integer|unique:organizations',
            'bic'            => 'nullable|digits:9|integer',
            'curaccount'     => 'nullable|digits:20|integer',
            'coraccount'     => 'nullable|digits:20|integer',                       
            'kpp'            => 'required_if:ownership,"organization"|nullable|digits:9|integer',
            'contact_person' => 'required|max:200',
            'email'          => 'required|email|max:200',
            'slug'           => 'required|unique:organizations',
            'logo'           => 'nullable|image|mimes:jpeg,jpg,png,gif',
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

        if ($files = $request->get('files')) {
            foreach ($files as $file) {
                $organization->files()->create([
                    'title' => $file['title'],
                    'path' => $file['path']
                ]);
            }
        }

        $admin = User::where('email', config('organization.admin'))->first();

        if($admin) {
            $admin->notify(new OrganizationNotification($organization));
        }

        Mail::to(config('organization.admin'))->send(new NewOrganization($organization));

        flash()->success("Ваше заявление на регистрацию компании принято и поступило на модерацию");

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
     * @throws \Exception
     */
    public function update(Request $request, Organization $organization)
    {
        $rules = [
            'ownership' => 'required|max:200',
            'inn' => 'required|max:200',
            'contact_person' => 'required|max:200',
            'email' => 'required|max:200',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,gif',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $organization->update($request->all());

        if ($organization->status == 'rejected') {
            $organization->status = 'moderation';

            $organization->save();
        }

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $organization->addLogo($request->file('logo'));
        }

        $this->addConnection($request, $organization);

        foreach ($organization->files as $file) {
            $file->delete();
        }

        if ($files = $request->get('files')) {
            foreach ($files as $file) {
                $organization->files()->create([
                    'title' => $file['title'],
                    'path' => $file['path']
                ]);
            }
        }

        if ($organization->status == 'moderation') {
            Mail::to(config('organization.admin'))->send(new NewOrganization($organization));

            flash()->success("Ваше заявление на регистрацию компании принято и поступило на модерацию");
        } else {
            flash()->success('Organization updated!');
        }

        return redirect(route('organizations.show', $organization));
    }

    /**
     * Update a resource in storage.
     *
     * @param Organization $organization
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function approve(Organization $organization)
    {
        $organization->status = 'approved';

        $organization->save();

        $this->updateNotification($organization);

        flash()->success('Organization approved!');

        return redirect()->back();
    }

    /**
     * Update a resource in storage.
     *
     * @param Organization $organization
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function reject(Organization $organization)
    {
        $organization->status = 'rejected';

        $organization->save();

        $this->updateNotification($organization);

        flash()->success('Organization rejected!');

        return redirect()->back();
    }

    /**
     * @param $organization
     */
    private function updateNotification($organization) {
        $notifications = auth()->user()
            ->notifications()
            ->where('type', 'App\Notifications\OrganizationNotification')
            ->get();


        foreach ($notifications as $notification) {
            if($notification['data']['id'] == $organization->id) {
                $notification = auth()->user()
                    ->notifications()
                    ->find($notification['id']);

                if($notification) {
                    $notification->update(['is_archived' => true]);
                }
            }
        }
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
