<x-layout>
    <x-slot:title>Login</x-slot:title>

    <form action="projects" method="post">
        @csrf
        
        <label for="name">Name</label>
        <input id="name" name="name">

        <button type="submit">Submit</button>
    </form>
</x-layout>