<x-layout>
    <x-slot:title>Login</x-slot:title>

    <form action="projects" method="post">
        @csrf
        
        <label for="name" class="text-gray-900 dark:text-white">Name</label>
        <input id="name" name="name">

        <button type="submit" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Submit</button>
    </form>
</x-layout>