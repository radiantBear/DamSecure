<?php

namespace App\Http\Controllers;

use App\Models\Data;
use Illuminate\Http\Request;

class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Laravel Sanctum is designed to provide users with API tokens. We're using it to
        // provide projects with API tokens, hence the naming discrepancy
        $project = auth()->user();

        $this->authorize('viewAny', [Data::class, $project]);

        return response()->json($project->project_data);
    }

    /**
     * Store a newly created resource in the database
     */
    public function store(Request $request)
    {
        // Laravel Sanctum is designed to provide users with API tokens. We're using it to
        // provide projects with API tokens, hence the naming discrepancy
        $project = auth()->user();

        $this->authorize('create', Data::class);

        $type = 'unknown';

        // NOTE: if there are changes to how data is parsed, be sure to update the
        // documentation on the homepage

        if ($request->header('Content-Type') === 'application/json') {
            $type = 'json';

            if (!json_validate($request->getContent())) {
                return response('Invalid JSON', 400);
            }
        } elseif ($request->header('Content-Type') === 'text/csv') {
            $type = 'csv';
        }

        $data = $project->project_data()->create([
            'type' => $type,
            'data' => $request->getContent()
        ]);

        return response($data->id, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Data $data)
    {
        $this->authorize('update', [Data::class, $data]);

        if (!json_validate($request->getContent())) {
            return response('Invalid JSON', 400);
        }

        $data->update(['data' => $request->getContent()]);

        return response('Updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $data)
    {
        $this->authorize('delete', [Data::class, Data::findOrFail($data)]);

        Data::destroy($data);

        if ($request->routeIs('api.*')) {
            return response('Deleted', 200);
        }

        return back();
    }
}
