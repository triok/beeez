<?php

namespace App\Http\Controllers;

use App\Http\Requests\CvRequest;
use App\Models\Vacancy;
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

        flash()->success('Ваш отклик создан.');

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
}
