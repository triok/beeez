<?php

namespace App\Http\Controllers\API;

use App\Models\Project;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProjectsController extends Controller
{
    public function notes(Request $request, Project $project)
    {
        if ($request->get('notes')) {
            $project->notes = $request->get('notes');

            $project->save();
        }

        return response()->json([
            'status' => 'success'
        ]);
    }
}
