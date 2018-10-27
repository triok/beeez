<?php

namespace App\Http\Controllers\API;

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
            $teams = Vacancy::where('name', 'LIKE', '%' . $request->q . '%')->take(10)->get();
        } else {
            $teams = Vacancy::query()->take(10)->get();
        }

        return response()->json($teams);
    }

    public function search(Request $request)
    {
        $vacancies = Vacancy::published();

        if ($request->get('specialization')) {
            $vacancies->where('specialization', $request->get('specialization'));
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
}
