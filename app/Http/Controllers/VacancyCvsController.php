<?php

namespace App\Http\Controllers;

use App\Http\Requests\CvRequest;
use App\Models\Cv;
use App\Models\Vacancy;
use App\Notifications\CvAdminNotification;
use App\Notifications\CvApprovedNotification;
use App\Notifications\CvDeclinedNotification;
use App\Notifications\CvUserNotification;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VacancyCvsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param Vacancy $vacancy
     * @return \Illuminate\Http\Response
     */
    public function create(Vacancy $vacancy)
    {
        if($vacancy->organization->user_id == auth()->id() ||
            $vacancy->cvs()->where('user_id', auth()->id())->count()) {

            flash()->warning('Вы же отклинулись на эту вакансию!');

            return redirect(route('vacancies.index'));
        }

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
        if($vacancy->organization->user_id == auth()->id() ||
            $vacancy->cvs()->where('user_id', auth()->id())->count()) {

            flash()->warning('Вы же отклинулись на эту вакансию!');

            return redirect(route('vacancies.index'));
        }

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

        if ($admin = $vacancy->organization->user) {
            $admin->notify(new CvAdminNotification($cv));
        }

        flash()->success('Вы отклинулись на вакансию "' . $vacancy->name . '", ожидайте решение работодателя.');

        return redirect(route('vacancies.index'));
    }

    /**
     * Destroy a resource in storage.
     *
     * @param Vacancy $vacancy
     * @param Cv $cv
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     * @throws \Exception
     */
    public function destroy(Vacancy $vacancy, Cv $cv)
    {
        $cv->delete();

        flash()->success('Отклик удален.');

        return redirect()->back();
    }

    /**
     * Update a resource in storage.
     *
     * @param Vacancy $vacancy
     * @param Cv $cv
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function approve(Vacancy $vacancy, Cv $cv)
    {
        $cv->update([
            'status' => 'approved',
            'answered_at' => Carbon::now(),
        ]);

        return redirect(route('vacancies.cvs.success', [$vacancy, $cv]));
    }

    /**
     * Update a resource in storage.
     *
     * @param Vacancy $vacancy
     * @param Cv $cv
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function reject(Vacancy $vacancy, Cv $cv)
    {
        $cv->update([
            'status' => 'declined',
            'answered_at' => Carbon::now(),
        ]);

        return redirect(route('vacancies.cvs.success', [$vacancy, $cv]));
    }

    /**
     * Update a resource in storage.
     *
     * @param Vacancy $vacancy
     * @param Cv $cv
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function success(Vacancy $vacancy, Cv $cv)
    {
        return view('vacancies.cvs.success', compact('vacancy', 'cv'));
    }

    /**
     * Update a resource in storage.
     *
     * @param Request $request
     * @param Vacancy $vacancy
     * @param Cv $cv
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function successStore(Request $request, Vacancy $vacancy, Cv $cv)
    {
        if ($recipient = User::find($cv->user_id)) {
            if($cv->status == 'declined') {
                $recipient->notify(new CvDeclinedNotification($request, $cv));
            }

            if($cv->status == 'approved') {
                $recipient->notify(new CvApprovedNotification($request, $cv));
            }
        }

        return redirect(route('vacancies.index'));
    }
}
