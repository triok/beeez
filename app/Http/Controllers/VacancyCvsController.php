<?php

namespace App\Http\Controllers;

use App\Http\Requests\CvRequest;
use App\Models\Vacancy;
use App\Notifications\CvAdminNotification;
use App\Notifications\CvUserNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VacancyCvsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Vacancy $vacancy
     * @return \Illuminate\Http\Response
     */
    public function create(Vacancy $vacancy)
    {
        return view('vacancies.cvs.create', compact('vacancy'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CvRequest $request
     * @param Vacancy $vacancy
     * @return void
     */
    public function store(CvRequest $request, Vacancy $vacancy)
    {
        $cv = $vacancy->addCv($request->all());

        if ($request->hasFile('cv') && $request->file('cv')->isValid()) {
            $cv->addFile($request->file('cv'));
        }

        if ($recipient = User::find(auth()->id())) {
            $recipient->notify(new CvUserNotification($cv));

            $recipient->notifications()
                ->whereNull('read_at')
                ->where('type', 'App\Notifications\CvUserNotification')
                ->update(['read_at' => Carbon::now()]);
        }

        if ($admin = User::where('email', config('vacancy.admin'))->first()) {
            $admin->notify(new CvAdminNotification($cv));
        }

        flash()->success('Вы отклинулись на вакансию "' . $vacancy->name . '", ожидайте решение работодателя.');

        return redirect(route('vacancies.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Update a resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function approve(Request $request)
    {

    }

    /**
     * Update a resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Exception
     */
    public function reject(Request $request)
    {

    }
}
