<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function list() {

    	//

    	return view('projects.projects-list');
    }
    public function create() {

    	//

    	return view('projects.project-create');
    }
    public function show() {

    	//

    	return view('projects.project-show');
    }          
}
