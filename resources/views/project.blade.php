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

    <div class="d-flex flex-column gap-4">
        <x-data.json-table :$json />

        <x-data.csv-table :$csv />

        <x-data.unknown-table :unknown-data=$unknown />
    </div>

    <x-api-token-display />
</x-layout>
