<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\TaskRequest;
use App\Transformers\TaskTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class TasksController extends ApiController
{
    protected $transformer;

    public function __construct(TaskTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $tasks = Auth::user()->tasks()->get();

        return $this->response(
            $this->transformer->transformCollection($tasks)
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TaskRequest $request
     * @return JsonResponse
     */
    public function store(TaskRequest $request)
    {
        $doDate = $request->get('do_date')
            ? date('Y-m-d', strtotime($request->get('do_date')))
            : null;

        Auth::user()->tasks()->create([
            'name' => $request->get('name'),
            'do_date' => $doDate,
        ]);

        return $this->index();
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $task = Auth::user()->tasks()->find($id);

        if (!$task) {
            return $this->response([], 404);
        }

        return $this->response(
            $this->transformer->transform($task->toArray())
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TaskRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(TaskRequest $request, $id)
    {
        $task = Auth::user()->tasks()->find($id);

        if (!$task) {
            return $this->response([], 404);
        }

        $task->update($request->only(['comment', 'completed']));

        return $this->index();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $task = Auth::user()->tasks()->find($id);

        if (!$task) {
            return $this->response([], 404);
        }

        $task->delete();

        return $this->index();
    }
}
