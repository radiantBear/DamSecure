<x-layout>
    <x-slot:title>{{ $project->name }}</x-slot:title>

    <a href="projects/{{ $project->uuid }}/token" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Rotate API token</a>

    <table>
        <tr>
            <th class="text-gray-900 dark:text-white">Data</th>
            <th class="text-gray-900 dark:text-white">Timestamp</th>
        </tr>
        @foreach ($data as $d)
        <tr>
            <td class="text-gray-900 dark:text-white">{{ $d->data }}</td>
            <td class="text-gray-900 dark:text-white">{{ $d->created_at }}</td>
        </tr>
        @endforeach
    </table>
</x-layout>
