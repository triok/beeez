<?php

namespace App\Http\Controllers\API;

use App\Models\Cv;
use App\Models\Organization;
use App\Models\Vacancy;
use App\Http\Controllers\Controller;
use App\Transformers\CvTransformer;
use App\Transformers\VacancyTransformer;
use Illuminate\Http\Request;

class CvsController extends Controller
{
    protected $transformer;

    public function __construct(CvTransformer $transformer)
    {
        $this->middleware('auth');

        $this->transformer = $transformer;
    }

    public function index(Request $request)
    {
        if ($request->get('q')) {
            $cvs = Cv::where('name', 'LIKE', '%' . $request->q . '%');
        } else {
            $cvs = Cv::query();
        }

        return response()->json($cvs->take(10)->get());
    }

    public function search(Request $request)
    {
        $cvs = Cv::where('user_id', auth()->id())->get();

        if (!$cvs->count()) {
            return response()->json(['data' => []]);
        }

        return response()->json(
            $this->transformer->transformCollection($cvs)
        );
    }
}
