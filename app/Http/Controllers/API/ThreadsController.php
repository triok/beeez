<?php

namespace App\Http\Controllers\API;

use App\Models\Thread;
use App\Transformers\ThreadTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ThreadsController extends Controller
{
    protected $transformer;

    function __construct(ThreadTransformer $transformer)
    {
        $this->transformer = $transformer;

        $this->middleware('auth');
    }

    public function index()
    {
        $threads = Thread::forUser(Auth::id())
            ->latest('updated_at')
            ->get();

        return response()->json($this->transformer->transformCollection($threads));
    }
}
