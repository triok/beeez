<?php

namespace App\Http\Controllers;

use App\Events\SocialEvent;
use App\Models\Billing\Billing;
use App\Models\User\UserSkills;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:update-profile',['only'=>['updateProfile','updateBio']]);
        $this->middleware('permission:read-profile',['only'=>['index']]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    function index()
    {
        /** @var User $user */
        $user = auth()->user();

        $user->socLinks = $user->socialLinks();

        return view('auth.account', compact('user'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function updateProfile(Request $request)
    {
        $id = Auth::user()->id;

        $rules = [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users,email,' . $id,
        ];

        if (Input::has('password')) {
            $rules2 = [
                'password' => 'min:6|confirmed',
                'password_confirmation' => 'required|min:6'
            ];
            $rules = array_collapse([$rules, $rules2]);
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::find($id);
        if (Input::has('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->email = $request->email;
        $user->name = $request->name;
        $user->updated_at = date('Y-m-d H:i:s');
        $user->stripe_public_key=$request->stripe_public_key;
        if($request->has('stripe_secret_key'))
            $user->stripe_secret_key = encrypt($request->stripe_secret_key);
        $user->save();

        flash()->success('Profile updated!');
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function updateBio(Request $request)
    {

        if ($request->ajax()) {
            event($response = new SocialEvent($request));

            return response()->json(['response' => $response->getResponse()]);
        }

        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {

            auth()->user()->updateAvatar($request->file('avatar'));
        }

        $id = Auth::user()->id;

        $rules = [
            'bio'   => 'nullable|max:2000',
            'avatar' => 'nullable|image|mimes:jpeg,jpg,png,gif',
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users,email,' . $id,
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        /** @var User $user */
        $user = auth()->user();
        $user->email = $request->email;
        $user->name = $request->name;
        $user->bio = $request->bio;
        $user->country = $request->country;
        $user->city = $request->city;
        $user->speciality = $request->get('speciality');

        $user->show_working_hours = ($request->get('show_working_hours') ? true : false);
        $user->working_hours = json_encode($request->get('day'));

        $user->save();

        //update skills
        if ($request->has('skills')) {
            $skills = explode(',', $request->skills);
            foreach ($skills as $skill) {
                UserSkills::firstOrCreate(['user_id' => Auth::user()->id, 'skill_id' => $skill]);
            }
        }

        //update services
        Auth::user()->services()->delete();
        if ($request->has('services') && is_array($request->get('services'))) {
            foreach ($request->get('services') as $service) {
                Auth::user()->services()->create([
                    'name' => $service
                ]);
            }
        }

        flash()->success('You profile has been updated!');

        return redirect()->to(url()->previous() . '#bio');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    function addPortfolio(Request $request)
    {
        if (Auth::user()->portfolio()->count() >= 20) {
            flash()->error('Можно добавить до 20 работ!');

            return redirect()->to(url()->previous() . '#examples');
        }

        $validator = Validator::make($request->all(), [
            'name'   => 'required|max:250',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $portfolio = Auth::user()->portfolio()->create([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
        ]);

        foreach ($request->file('portfolio') as $file) {
            $path = $file->store('public/portfolio');

            $portfolio->files()->create([
                'file' => $path,
                'original_name' => $file->getClientOriginalName(),
            ]);
        }

        flash()->success('Пример работы добавлен.');

        return redirect()->to(url()->previous() . '#examples');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    function deletePortfolio($id)
    {
        $portfolio = Auth::user()->portfolio()->where('id', $id)->first();

        if($portfolio) {
            $portfolio->files()->delete();

            $portfolio->delete();

            flash()->success('Пример работы удален.');
        } else {
            flash()->error('Пример работы не найден.');
        }

        return redirect()->to(url()->previous() . '#examples');
    }
}