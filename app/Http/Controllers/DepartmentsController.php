<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Project;
use App\Models\Department;
use App\Models\DepartmentType;
use App\Models\DepartmentUsers;
use App\Notifications\DepartmentUserNotification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentsController extends Controller
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

        return view('departments.index', compact('organization', 'connections'));
    }

    /**
     * Display the specified resource.
     *
     * @param Department $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        $userIsAdmin = $this->userIsDepartmentAdmin($department);

        $userIsConnected = $this->userIsConnected($department);

        $connections = DepartmentUsers::where('department_id', $department->id)->get();

        return view('departments.show', compact('department', 'connections', 'userIsAdmin', 'userIsConnected'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departmentTypes = DepartmentType::all();

        $users = User::where('id', '!=', auth()->id())->get();

        return view('departments.create', compact('departmentTypes', 'users'));
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
            'department_type_id' => 'required',
            'slug' => 'required|unique:departments',
            'logo' => 'nullable|image|mimes:jpeg,jpg,png,gif',
        ];

        $attributes = $request->all();

        $attributes['slug'] = str_slug($request->get('name', ''));

        $validator = Validator::make($attributes, $rules, ['unique' => 'Название не уникально.']);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $attributes['user_id'] = auth()->user()->id;

        $department = Department::create($attributes);

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $department->addLogo($request->file('logo'));
        }

        DepartmentUsers::create([
            'department_id' => $department->id,
            'user_id' => auth()->id(),
            'position' => 'Создатель',
            'is_approved' => true
        ]);

        $this->addConnection($request, $department);

        flash()->success('Department saved!');

        return redirect(route('departments.show', $department));
    }

    /**
     * Show the form for editing a resource.
     *
     * @param Department $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        if (!$this->userIsDepartmentAdmin($department)) {
            flash()->error('Access denied!');

            return redirect()->back();
        }

        $departmentTypes = DepartmentType::all();

        $connections = DepartmentUsers::where('department_id', $department->id)->get();

        $users = User::where('id', '!=', auth()->id())->get();

        return view('departments.edit', compact('department', 'departmentTypes', 'connections', 'users'));
    }

    /**
     * Update a resource in storage.
     *
     * @param Request $request
     * @param Department $department
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Exception
     */
    public function update(Request $request, Department $department)
    {
        if (!$this->userIsDepartmentAdmin($department)) {
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

        $department->description = $request->get('description', '');
        $department->save();

        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $department->addLogo($request->file('logo'));
        }

        $this->addConnection($request, $department);

        flash()->success('Department updated!');

        return redirect(route('departments.show', $department));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function projects()
    {
        $departmentIds = auth()->user()->departments()->pluck('department_id')->toArray();

        $departments = Department::where('user_id', auth()->id())
            ->orWhereIn('id', $departmentIds)
            ->get();

        $departmentProjects = [];

        foreach ($departments as $department) {
            $projects = Project::where('department_id', $department->id)->orderBy('sort_order')->orderBy('name')->get();

            $departmentProjects[$department->id] = $projects;
        }

        $departmentSelected = request('department_id', ($departments->first() ? $departments->first()->id : 0));

        return view('departments.projects', compact('projects', 'departments', 'departmentProjects', 'departmentSelected'));
    }

    /**
     * Destroy a resource in storage.
     *
     * @param Department $department
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Exception
     */
    public function destroy(Department $department)
    {
        if (!$this->userIsDepartmentAdmin($department)) {
            flash()->error('Access denied!');

            return redirect()->back();
        }

        DepartmentUsers::where('department_id', $department->id)
            ->delete();

        $department->delete();

        flash()->success('Команда удалена.');

        return redirect(route('departments.index'));
    }

    /**
     * Update a resource in storage.
     *
     * @param Department $department
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Exception
     */
    public function disconnect(Department $department)
    {
        if (auth()->id() == $department->user_id || !$this->userIsConnected($department)) {
            flash()->error('Access denied!');

            return redirect()->back();
        }

        DepartmentUsers::where('department_id', $department->id)
            ->where('user_id', auth()->id())
            ->delete();

        flash()->success('Вы успешно покинули команду.');

        return redirect(route('departments.mydepartments'));
    }

    /**
     * Add connections.
     *
     * @param Request $request
     * @param Department $department
     * @throws \Exception
     */
    protected function addConnection(Request $request, Department $department)
    {
        if ($request->has('connections')) {
            $connectionIds = DepartmentUsers::where('department_id', $department->id)
                ->pluck('position', 'user_id')
                ->toArray();

            foreach ($request->get('connections') as $user_id => $connection) {
                if (isset($connectionIds[$user_id])) {
                    if ($connectionIds[$user_id] != $connection['position']) {
                        DepartmentUsers::where('department_id', $department->id)
                            ->where('user_id', $user_id)
                            ->update(['position' => $connection['position']]);
                    }

                    unset($connectionIds[$user_id]);
                } else {
                    if($user_id != auth()->id()) {
                        $departmentUser = DepartmentUsers::create([
                            'department_id' => $department->id,
                            'user_id' => $user_id,
                            'position' => $connection['position']
                        ]);

                        if ($recipient = User::find($user_id)) {
                            $recipient->notify(new DepartmentUserNotification($departmentUser));
                        }
                    }
                }
            }

            foreach ($connectionIds as $user_id => $position) {
                DepartmentUsers::where('department_id', $department->id)
                    ->where('user_id', $user_id)
                    ->where('user_id', '!=', auth()->id())
                    ->delete();
            }
        } else {
            DepartmentUsers::where('department_id', $department->id)
                ->where('user_id', '!=', auth()->id())
                ->delete();
        }
    }

    private function userIsDepartmentAdmin($department)
    {
        if (auth()->id() == $department->user_id) {
            return true;
        }

        if ($connection = DepartmentUsers::where('department_id', $department->id)
            ->where('user_id', auth()->id())
            ->where('is_admin', true)
            ->first()) {

            return true;
        }

        return false;
    }

    private function userIsConnected($department)
    {
        if (auth()->id() == $department->user_id) {
            return false;
        }

        if ($connection = DepartmentUsers::where('department_id', $department->id)
            ->where('user_id', auth()->id())
            ->first()) {

            return true;
        }

        return false;
    }
}
