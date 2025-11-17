<x-layout>
    <x-slot:title>Projects</x-slot:title>

    <a href="projects/create" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Create new project</a>

    @if ($projects->isNotEmpty())
    <ul>
        @foreach ($projects as $p)
        <li><a href="projects/{{ $p->uuid }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">{{ $p->name }}</a></li>
        @endforeach
    </ul>
    @else
    <p class="text-gray-900 dark:text-white">No projects yet.</p>
    @endif
</x-layout>