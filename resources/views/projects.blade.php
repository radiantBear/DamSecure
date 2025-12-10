<x-layout>
    <x-slot:title>Projects</x-slot:title>

    <div class="row">
        <div class="col-sm-4"><!-- Offset for the button on the other end --></div>
        <div class="col text-center"><h1>Projects</h1></div>
        <div class="col-sm-4 d-flex gap-2 justify-content-end align-items-center">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                <i class="fa-solid fa-database"></i>
                New project
            </button>
        </div>
    </div>

    <div class="row">
        <div class="col">
            @if ($projects->isNotEmpty())
            <table class="table table-responsive table-striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Created</th>
                        <th>Last Upload</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($projects as $p)
                    <tr>
                        <td><a href="projects/{{ $p->uuid }}">{{ $p->name }}</a></td>
                        <td>{{ $p->created_at }}</td>
                        <td>{{ $p->latest_upload?->updated_at ?: 'None' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <ul>
                
            </ul>
            @else
            <p>No projects yet.</p>
            @endif
        </div>
    </div>

    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="createModalLabel">Create a new project</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
                </div>
                <form action="projects" method="post">
                    @csrf
                    <div class="modal-body">
                        <input id="name" name="name" placeholder="Name" class="form-control">
                        <div class="d-flex gap-2 justify-content-end mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>