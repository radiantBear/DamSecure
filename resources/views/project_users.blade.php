<x-layout>
    <x-slot:title>{{ $project->name }} Permissions</x-slot:title>

    {{ $errors }}

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
                <td>
                    <form method="post" action="permissions/{{ $p->id }}">
                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}
                        <select name="role" onchange="this.form.submit()">
                            <option value="owner" {{ $p->role === 'owner' ? 'selected' : '' }}>Owner</option>
                            <option value="contributor" {{ $p->role === 'contributor' ? 'selected' : '' }}>Contributor</option>
                            <option value="viewer" {{ $p->role === 'viewer' ? 'selected' : '' }}>Viewer</option>
                        </select>
                    </form>
                    <form method="post" action="permissions/{{ $p->id }}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button type="submit"><i class="fa-solid fa-trash"></i></button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <form method="post">
        {{ csrf_field() }}
        <input name="onid" type="text" placeholder="ONID">
        <select name="role" onchange="this.form.submit()">
            <option value="contributor">Contributor</option>
            <option value="viewer">Viewer</option>
        </select>
        <button type="submit">Add</button>
    </form>
</x-layout>
