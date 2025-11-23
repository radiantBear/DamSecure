<?php

namespace App\Http\Controllers;

use App\Models;
use Illuminate\Http\Request;

class ProjectUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Models\Project $project)
    {
        $this->authorize('viewAny', [Models\ProjectUser::class, $project]);

        $permissions = $project->user_permissions()->with('user')->with('project')->get();

        return view('project_users', ['project' => $project, 'permissions' => $permissions]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Models\Project $project)
    {
        $this->authorize('create', [Models\ProjectUser::class, $project]);

        $validated = $request->validate([
            'onid' => 'required',
            'role' => 'required|in:owner,contributor,viewer'
        ]);

        if (Models\User::where('onid', $validated['onid'])->doesntExist())
        {
            return back()->withErrors([
                'onid' => 'User does not exist. Have they logged into DamSecure yet?'
            ]);
        }

        if ($project->user_permissions()
            ->whereIn('user_id', Models\User::select('id')->where('onid', $validated['onid']))
            ->exists()
        ) {
            return back()->withErrors([
                'onid' => 'User is already assigned to the project.'
            ]);
        }
        
        $project_user = new Models\ProjectUser();
        $project_user->project_id = $project->id;
        $project_user->role = $validated['role'];
        $project_user->user_id = Models\User::where('onid', $validated['onid'])->first()->id;
        
        $project_user->save();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Models\ProjectUser $project_user)
    {
        $this->authorize('update', $project_user);

        $validated = $request->validate([
            'role' => 'required|in:owner,contributor,viewer'
        ]);

        $project_user->role = $validated['role'];
        
        $project_user->save();

        return redirect("projects/{$project_user->project->uuid}/permissions");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Models\ProjectUser $project_user)
    {
        $this->authorize('delete', $project_user);

        $project_user->delete();

        return redirect("projects/{$project_user->project->uuid}/permissions");
    }
}
