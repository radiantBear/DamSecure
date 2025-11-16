<x-layout>
    <x-slot:title>Login</x-slot:title>

    <form action="/authenticate">
        <label for="osuuid" class="text-gray-900 dark:text-white">OSUUID</label>
        <input id="osuuid" name="osuuid">
        
        <label for="onid" class="text-gray-900 dark:text-white">ONID</label>
        <input id="onid" name="onid">
        
        <label for="firstName" class="text-gray-900 dark:text-white">First Name</label>
        <input id="firstName" name="firstName">
        
        <label for="lastName" class="text-gray-900 dark:text-white">Last Name</label>
        <input id="lastName" name="lastName">
        
        <label for="email" class="text-gray-900 dark:text-white">Email</label>
        <input id="email" name="email">

        <button type="submit" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">Submit</button>
    </form>
</x-layout>