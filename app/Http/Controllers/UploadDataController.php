<?php

namespace App\Http\Controllers;

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
