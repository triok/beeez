<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function show() {

    	//

    	return view('projects.projects-list');
    }
    public function create() {

    	//

    	return view('projects.project-create');
    }    
}
