<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

    function index(){
        $projects = Project::query()
            ->select('title', 'description', 'image')
            ->orderByDesc('created_at')
            ->paginate(6);

        return view('projects.index', compact('projects'));
    }

    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }
}
