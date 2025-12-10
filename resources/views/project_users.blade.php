<x-layout>
    <x-slot:title>{{ $project->name }} Permissions</x-slot:title>

    <div class="row">
        <div class="col text-center"><h1>{{ $project->name }} Permissions</h1></div>
    </div>

    <table class="table table-responsive table-striped">
        <thead>
            <tr>
                <th>ONID</th>
                <th>Permissions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permissions as $p)
            <tr>
                <td>{{ $p->user->onid }}</td>
                <td class="d-flex gap-4">
                    <form method="post" action="permissions/{{ $p->id }}" class="flex-grow-1">
                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}
                        <select name="role" onchange="this.form.submit()" class="form-select" @cannot('update', $p) disabled @endcan>
                            <option value="owner" {{ $p->role === 'owner' ? 'selected' : '' }}>Owner</option>
                            <option value="contributor" {{ $p->role === 'contributor' ? 'selected' : '' }}>Contributor</option>
                            <option value="viewer" {{ $p->role === 'viewer' ? 'selected' : '' }}>Viewer</option>
                        </select>
                    </form>
                    @can('delete', $p)
                    <form method="post" action="permissions/{{ $p->id }}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                    </form>
                    @endcan
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row">
        <div class="col">
            <ul>
                <li><strong>Owner:</strong> has full permissions for the project</li>
                <li><strong>Contributors:</strong> can view project data and rotate the API key</li>
                <li><strong>Viewers:</strong> can view project data (including permissions)</li>
            </ul>
        </div>
    </div>

    @can('create', [App\Models\ProjectUser::class, $project])
    <form method="post" class="row needs-validation {{ $errors->isNotEmpty() ? 'was-validated' : '' }}" novalidate>
        {{ csrf_field() }}
        <div class="col">
            <input name="onid" type="text" placeholder="ONID" class="form-control" required>
            @error('onid')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col">
            <select name="role" class="form-select">
                <option value="contributor">Contributor</option>
                <option value="viewer">Viewer</option>
            </select>
        </div>
        <div class="col">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-user-plus"></i>
                Add
            </button>
        </div>
    </form>
    @endcan
</x-layout>
