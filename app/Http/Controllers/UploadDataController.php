<?php

namespace App\Http\Controllers;

use App\Models;
use App\Models\UploadData;
use App\Services\DataService;
use Illuminate\Http\Request;

class UploadDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Laravel Sanctum is designed to provide users with API tokens. We're using it to
        // provide projects with API tokens, hence the naming discrepancy
        $project = auth()->user();

        $this->authorize('viewAny', [UploadData::class, $project]);

        return response()->json($project->project_upload_data);
    }

    /**
     * Download the resource as CSV.
     */
    public function download(Request $request, Models\Project $project)
    {
        $this->authorize('view', [Models\Project::class, $project]);

        $validated = $request->validate([
            'type' => 'required|in:json,csv,unknown'
        ]);

        $data = $project->project_upload_data()->where('type', $validated['type'])->get();
        if ($data->count() < 1)
            return back()->withErrors([
                'downloadError' => 'No ' . $validated['type'] . '-type records'
            ]);

        if ($validated['type'] === 'json')
            $tabulated_data = DataService::jsonToTable($data);
        else if ($validated['type'] === 'csv')
            $tabulated_data = DataService::csvToTable($data);
        else
            $tabulated_data = DataService::unknownToTable($data);

        return response()->streamDownload(function () use ($tabulated_data) {
            dump('also made it here');
            $handle = fopen('php://output', 'w');
            try {
                foreach ($tabulated_data as $row)
                    fputcsv($handle, $row);
            }
            finally {
                fclose($handle);
            }
        }, "{$project->name}_{$validated['type']}_data.csv");
    }

    /**
     * Store a newly created resource in the database
     */
    public function store(Request $request)
    {
        // Laravel Sanctum is designed to provide users with API tokens. We're using it to
        // provide projects with API tokens, hence the naming discrepancy
        $project = auth()->user();

        $this->authorize('create', UploadData::class);

        $type = DataService::getDataType($request);

        [$valid, $err] = DataService::validateData($request->getContent(), $type);
        if (!$valid) {
            return response($err, 400);
        }

        $data = $project->project_upload_data()->create([
            'type' => $type,
            'data' => $request->getContent()
        ]);

        return response($data->id, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UploadData $data)
    {
        $this->authorize('update', [UploadData::class, $data]);

        $type = DataService::getDataType($request);

        [$valid, $err] = DataService::validateData($request->getContent(), $type);
        if (!$valid) {
            return response($err, 400);
        }

        $data->update(['data' => $request->getContent(), 'type' => $type]);

        return response('Updated', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $data)
    {
        $this->authorize('delete', [UploadData::class, UploadData::findOrFail($data)]);

        UploadData::destroy($data);

        if ($request->routeIs('api.*')) {
            return response('Deleted', 200);
        }

        return back();
    }
}
