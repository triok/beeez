<?php

namespace App\Http\Controllers\API;

use App\Models\Organization;
use App\Models\Vacancy;
use App\Http\Controllers\Controller;
use App\Transformers\VacancyTransformer;
use Illuminate\Http\Request;

class VacanciesController extends Controller
{
    protected $transformer;

    public function __construct(VacancyTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function index(Request $request)
    {
        if ($request->get('q')) {
            $teams = Vacancy::where('name', 'LIKE', '%' . $request->q . '%');
        } else {
            $teams = Vacancy::query();
        }

        if ($request->has('organization_id')) {
            $teams->where('organization_id', $request->get('organization_id'));
        }

        return response()->json($teams->take(10)->get());
    }

    public function search(Request $request)
    {
        $organization = $this->getOrganization($request);

        if ($organization && $request->get('all') && $organization->user_id == auth()->id()) {
            $vacancies = Vacancy::where('organization_id', $organization->id);
        } else if ($organization) {
            $vacancies = Vacancy::published()->where('organization_id', $organization->id);
        } else {
            $vacancies = Vacancy::published();
        }

        if ($request->get('specialization')) {
            $vacancies->where('specialization', $request->get('specialization'));
        }

        if($request->get('specializations')) {
            $specializations = json_decode($request->get('specializations'), true);

            $vacancies->whereIn('specialization', $specializations);
        }

        $vacancies = $vacancies->get();

        if ($request->has('favorite')) {
            $vacancies = $vacancies->filter(function ($vacancy) {
                return $vacancy->isFavorited();
            });
        }

        if ($request->has('response')) {
            $vacancies = $vacancies->filter(function ($vacancy) {
                return false; // todo: add functionality
            });
        }

        if (!$vacancies->count()) {
            return response()->json(['data' => []]);
        }

        return response()->json(
            $this->transformer->transformCollection($vacancies)
        );
    }

    protected function getOrganization(Request $request)
    {
        if ($request->has('organization_id')) {
            return Organization::find($request->get('organization_id'));
        }

        return null;
    }
}
