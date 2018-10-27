<?php

namespace App\Http\Controllers;

use App\Models\Vacancy;

class VacanciesController extends Controller
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
        return view('vacancies.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  Vacancy $vacancy
     * @return \Illuminate\Http\Response
     */
    public function show(Vacancy $vacancy)
    {
        return view('vacancies.show', compact('vacancy'));
    }

    /**
     * Update a resource in storage.
     *
     * @param Vacancy $vacancy
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function favorite(Vacancy $vacancy)
    {
        $vacancy->setFavorited();

        flash()->success('Вакансия добавлена в закладки!');

        return redirect()->back();
    }

    /**
     * Update a resource in storage.
     *
     * @param Vacancy $vacancy
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|void
     */
    public function unfavorite(Vacancy $vacancy)
    {
        $vacancy->setUnfavorited();

        flash()->success('Вакансия удалена с закладок!');

        return redirect()->back();
    }
}
