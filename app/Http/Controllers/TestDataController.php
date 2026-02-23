<?php

namespace App\Http\Controllers;

use App\Models;
use Illuminate\Http\Request;

class TestDataController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show()
    {
        $this->authorize('view', Models\TestData::class);

        return response(auth()->user()->project_test_data->data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Models\TestData $data)
    {
        $this->authorize('update', [Models\TestData::class, $data]);

        $data->update(['data' => $request->input('data')]);

        return redirect("projects/{$data->project->uuid}");
    }
}
