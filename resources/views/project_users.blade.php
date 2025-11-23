<x-layout>
    <x-slot:title>{{ $project->name }} Permissions</x-slot:title>

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
                        <select name="role" onchange="this.form.submit()" class="form-select">
                            <option value="owner" {{ $p->role === 'owner' ? 'selected' : '' }}>Owner</option>
                            <option value="contributor" {{ $p->role === 'contributor' ? 'selected' : '' }}>Contributor</option>
                            <option value="viewer" {{ $p->role === 'viewer' ? 'selected' : '' }}>Viewer</option>
                        </select>
                    </form>
                    <form method="post" action="permissions/{{ $p->id }}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <form method="post" class="row needs-validation {{ $errors->isNotEmpty() ? 'was-validated' : '' }}" novalidate>
        {{ csrf_field() }}
        <div class="col">
            <input name="onid" type="text" placeholder="ONID" class="form-control" required>
            @error('onid')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col">
            <select name="role" onchange="this.form.submit()" class="form-select">
                <option value="contributor">Contributor</option>
                <option value="viewer">Viewer</option>
            </select>
        </div>
        <div class="col">
            <button type="submit" class="btn btn-primary">Add</button>
        </div>
    </form>
</x-layout>
