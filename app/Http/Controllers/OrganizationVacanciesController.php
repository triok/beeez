<?php

namespace App\Http\Controllers;

use App\Http\Requests\VacancyRequest;
use App\Models\Organization;
use App\Models\Vacancy;
use Carbon\Carbon;

class OrganizationVacanciesController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('organization.owner');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Organization $organization
     * @return void
     */
    public function index(Organization $organization)
    {
        $vacancies = $organization->vacancies()
            ->orderBy('created_at', 'desc')
            ->paginate(request('count', 20));

        return view('organizations.vacancies.index', compact('organization', 'vacancies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Organization $organization
     * @return void
     */
    public function create(Organization $organization)
    {
        return view('organizations.vacancies.create', compact('organization'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param VacancyRequest $request
     * @param Organization $organization
     * @return void
     */
    public function store(VacancyRequest $request, Organization $organization)
    {
        $vacancy = $organization->vacancies()->create($request->all());

        if ($request->get('button') == 'publish') {
            $vacancy->published_at = Carbon::now();
            $vacancy->save();

            flash()->success('Вакансия опубликована.');
        } else {
            flash()->success('Вакансия создана.');
        }

        return redirect(route('organizations.vacancies.index', $organization));
    }

    /**
     * Display the specified resource.
     *
     * @param  Vacancy $vacancy
     * @param Organization $organization
     * @return void
     */
    public function show(Organization $organization, Vacancy $vacancy)
    {
        return view('organizations.vacancies.show', compact('organization', 'vacancy'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vacancy $vacancy
     * @param Organization $organization
     * @return void
     */
    public function edit(Organization $organization, Vacancy $vacancy)
    {
        return view('organizations.vacancies.edit', compact('organization', 'vacancy'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param VacancyRequest $request
     * @param Organization $organization
     * @param  Vacancy $vacancy
     * @return void
     */
    public function update(VacancyRequest $request, Organization $organization, Vacancy $vacancy)
    {
        $vacancy = $organization->vacancies()->find($vacancy->id);

        if ($vacancy) {
            $vacancy->update($request->all());

            if ($request->get('button') == 'publish') {
                $vacancy->published_at = Carbon::now();
                $vacancy->save();

                flash()->success('Вакансия опубликована.');
            } else {
                flash()->success('Вакансия обновлена.');
            }
        }

        return redirect(route('organizations.vacancies.index', $organization));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Organization $organization
     * @param  Vacancy $vacancy
     * @throws \Exception
     * @return void
     */
    public function destroy(Organization $organization, Vacancy $vacancy)
    {
        $vacancy = $organization->vacancies()->find($vacancy->id);

        if ($vacancy) {
            $vacancy->delete();

            flash()->success('Вакансия удалена.');
        }

        return redirect(route('organizations.vacancies.index', $organization));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Organization $organization
     * @param  Vacancy $vacancy
     * @return void
     */
    public function publish(Organization $organization, Vacancy $vacancy)
    {
        $vacancy = $organization->vacancies()->find($vacancy->id);

        if ($vacancy) {
            $vacancy->published_at = Carbon::now();
            $vacancy->save();

            flash()->success('Вакансия опубликована.');
        }

        return redirect(route('organizations.vacancies.index', $organization));
    }
}
