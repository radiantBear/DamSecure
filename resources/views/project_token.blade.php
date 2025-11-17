<x-layout>
    <x-slot:title>{{ $project->name }} API Token</x-slot:title>

    <p class="text-gray-900 dark:text-white">
        Your new API token is <code>{{ $token }}</code>. 
        Be sure to save this token; you won't be able to view it again.
    </p>
    <p class="text-gray-900 dark:text-white">
        Your previous token has been invalidated and can no longer be used.
    </p>
</x-layout>
