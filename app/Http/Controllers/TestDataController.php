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

        $data = auth()->user()->project_test_data;
        Models\TestData::withoutTimestamps(function () use ($data) {
            $data->increment('latest_times_retrieved');
            $data->increment('total_times_retrieved');
        });

        return response($data->data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Models\TestData $data)
    {
        $this->authorize('update', [Models\TestData::class, $data]);

        $data->update([
            'data' => $request->input('data'),
            'latest_times_retrieved' => 0
        ]);

        return redirect("projects/{$data->project->uuid}");
    }
}
