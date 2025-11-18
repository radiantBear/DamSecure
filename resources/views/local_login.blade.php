<x-layout>
    <x-slot:title>Login</x-slot:title>

    <form action="authenticate">
        <label for="osuuid">OSUUID</label>
        <input id="osuuid" name="osuuid">
        
        <label for="onid">ONID</label>
        <input id="onid" name="onid">
        
        <label for="firstName">First Name</label>
        <input id="firstName" name="firstName">
        
        <label for="lastName">Last Name</label>
        <input id="lastName" name="lastName">
        
        <label for="email">Email</label>
        <input id="email" name="email">

        <button type="submit">Submit</button>
    </form>
</x-layout>