<x-layout>
    <x-slot:title>{{ $project->name }}</x-slot:title>

    <a href="projects/{{ $project->uuid }}/permissions">Permissions</a>
    <a href="projects/{{ $project->uuid }}/token">Rotate API token</a>

    <table>
        <tr>
            <th>Data</th>
            <th>Timestamp</th>
        </tr>
        @foreach ($data as $d)
        <tr>
            <td>{{ $d->data }}</td>
            <td>{{ $d->created_at }}</td>
        </tr>
        @endforeach
    </table>
</x-layout>
