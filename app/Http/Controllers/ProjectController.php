<?php

namespace App\Http\Controllers;

use App\Models;
use App\Services\DataService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = auth()->user()->projects()->with('latest_upload')->get();

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

        $token = $project->createToken('upload_token', ['*'], now()->addYear());

        return redirect("/projects/{$project->uuid}")
            ->with([
                'apiToken' => $token->plainTextToken,
                'tokenExpiration' => $token->accessToken->expires_at
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Models\Project $project)
    {
        $this->authorize('view', $project);

        $data = DataService::splitData($project->project_data);

        $jsonFields = DataService::getJsonFields($data['json']);
        $csvLength = DataService::getCsvLength($data['csv']);

        return view('project', [
            'project' => $project,
            'json' => [ 'fields' => $jsonFields, 'data' => $data['json']],
            'csv' => [ 'length' => $csvLength, 'data' => $data['csv']],
            'unknown' => $data['unknown']
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
    public function rotate_token(Request $request, Models\Project $project)
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'expiration' => 'required|in:day,week,month,year,never'
        ]);

        switch ($validated['expiration']) {
            case 'day':
                $expiration = now()->addDay();
                break;
            case 'week':
                $expiration = now()->addWeek();
                break;
            case 'month':
                $expiration = now()->addMonth();
                break;
            case 'year':
                $expiration = now()->addYear();
                break;
            case 'never':
                $expiration = null;
                break;
        }

        $project->tokens()->delete();
        $token = $project->createToken('upload_token', ['*'], $expiration);

        return redirect("/projects/{$project->uuid}")
            ->with([
                'apiToken' => $token->plainTextToken,
                'tokenExpiration' => $token->accessToken->expires_at
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Models\Project $project)
    {
        //
    }
}
