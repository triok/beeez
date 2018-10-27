<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Vacancy;
use Illuminate\Http\Request;

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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Organization $organization
     * @return void
     */
    public function create(Organization $organization)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Organization $organization
     * @return void
     */
    public function store(Request $request, Organization $organization)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vacancy $vacancy
     * @param Organization $organization
     * @return void
     */
    public function show(Organization $organization, Vacancy $vacancy)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Vacancy $vacancy
     * @param Organization $organization
     * @return void
     */
    public function update(Request $request, Organization $organization, Vacancy $vacancy)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Organization $organization
     * @param  \App\Models\Vacancy $vacancy
     * @return void
     */
    public function destroy(Organization $organization, Vacancy $vacancy)
    {
        //
    }
}
