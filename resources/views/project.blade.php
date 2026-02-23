<x-layout>
    <x-slot:title>{{ $project->name }}</x-slot:title>

    <div class="row">
        <div class="col-lg-4"><!-- Offset for the button on the other end --></div>
        <div class="col text-center"><h1>{{ $project->name }}</h1></div>
        <div class="col-lg-4 d-flex gap-2 justify-content-end align-items-center">
            <a href="projects/{{ $project->uuid }}/permissions" class="btn btn-primary">
                <i class="fa-solid fa-id-card"></i> Permissions
            </a>
        </div>
    </div>

    <div class="d-flex flex-column gap-5">
        <div class="d-flex flex-column gap-4">
            <div>
                <h2>Upload Data</h2>
                <p>
                    All data uploaded to <code>/data</code> will be displayed here. JSON and
                    CSV data will be parsed into tables for easy viewing!
                </p>
            </div>
    
            <x-data.json-table :$json />
    
            <x-data.csv-table :$csv />
    
            <x-data.unknown-table :unknown-data=$unknown />
        </div>
    
        <div class="d-flex flex-column gap-4">
            <div>
                <h2>Test Data</h2>
                <p>
                    This payload will be emitted verbaitum from <code>/data/test</code>.
                    Use it to test that your project can receive data properly!
                </p>
            </div>
    
            <form method="post" action="data/test/{{ $project->project_test_data->id }}" class="row">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                <div class="col-lg-10">
                    <textarea name="data" class="form-control font-monospace">
                        {{ $project->project_test_data->data }}
                    </textarea>
                </div>
                <div class="col-sm-2 d-flex align-items-center">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    <x-api-token-display />
</x-layout>
