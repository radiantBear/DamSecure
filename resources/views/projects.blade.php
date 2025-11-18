<x-layout>
    <x-slot:title>Projects</x-slot:title>

    <a href="projects/create">Create new project</a>

    @if ($projects->isNotEmpty())
    <ul>
        @foreach ($projects as $p)
        <li><a href="projects/{{ $p->uuid }}">{{ $p->name }}</a></li>
        @endforeach
    </ul>
    @else
    <p>No projects yet.</p>
    @endif
</x-layout>