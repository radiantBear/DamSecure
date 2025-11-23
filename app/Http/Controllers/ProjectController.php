<?php

namespace App\Http\Controllers;

use App\Models;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = auth()->user()->projects;

        return view('projects', ['projects' => $projects]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Models\Project::class);

        $validated = $request->validate([
            'name' => 'required|max:128'
        ]);

        $project = new Models\Project();
        $project->uuid = Str::uuid();
        $project->name = $validated['name'];
        
        $project->save();

        $owner = new Models\ProjectUser();
        $owner->project_id = $project->id;
        $owner->user_id = auth()->user()->id;
        $owner->role = 'owner';
        $owner->save();

        $token = $project->createToken('upload_token');

        return redirect("/projects/{$project->uuid}")
            ->with(['apiToken' => $token->plainTextToken]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Models\Project $project)
    {
        $this->authorize('view', $project);
        
        return view('project', [
            'project' => $project,
            'data' => $project->project_data
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Models\Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Models\Project $project)
    {
        //
    }

    /**
     * Rotate the API token used to upload data.
     */
    public function rotate_token(Models\Project $project)
    {
        $this->authorize('update', $project);

        $project->tokens()->delete();
        $token = $project->createToken('upload_token');

        return redirect("/projects/{$project->uuid}")
            ->with(['apiToken' => $token->plainTextToken]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Models\Project $project)
    {
        //
    }
}
