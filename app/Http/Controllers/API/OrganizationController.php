<?php

namespace App\Http\Controllers\API;

use App\Models\Organization;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index(Request $request)
    {
        if ($request->get('q')) {
            $organizations = Organization::approved()->where('name', 'LIKE', '%' . $request->q . '%')->take(10)->get();
        } else {
            $organizations = Organization::approved()->query()->take(10)->get();
        }

        return response()->json($organizations);
    }
}
