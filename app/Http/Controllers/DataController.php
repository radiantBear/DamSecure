<?php

namespace App\Http\Controllers;

use App\Models\Data;
use Illuminate\Http\Request;

class DataController extends Controller
{
    /**
     * Store a newly created resource in the database
     */
    public function store(Request $request)
    {
        // Laravel Sanctum is designed to provide users with API tokens. We're using it to
        // provide projects with API tokens, hence the naming discrepancy
        $project = auth()->user();
        
        $this->authorize('create', Data::class);

        if (!json_validate($request->getContent()))
            return response('Invalid JSON', 400);

        $project->project_data()->create(['data' => $request->getContent()]);

        return response('Created', 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Data $data)
    {
        $this->authorize('update', [Data::class, $data]);

        if (!json_validate($request->getContent()))
            return response('Invalid JSON', 400);

        $data->update(['data' => $request->getContent()]);

        return response('Updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $data)
    {
        $this->authorize('delete', [Data::class, Data::findOrFail($data)]);

        Data::destroy($data);
    }
}
