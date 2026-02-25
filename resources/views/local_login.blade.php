<x-layout>
    <x-slot:title>Login</x-slot:title>

    <form action="authenticate">
        <div class="row g-3">
            <div class="col-2">
                <input id="osuuid" name="osuuid" placeholder="OSUUID" aria-label="OSUUID" type="number" class="form-control">
            </div>
            <div class="col-2">
                <input id="onid" name="onid" placeholder="ONID" aria-label="ONID" type="text" class="form-control">
            </div>
            <div class="col-2">
                <input id="firstName" name="firstName" placeholder="First Name" aria-label="First Name" type="text" class="form-control">
            </div>
            <div class="col-2">
                <input id="lastName" name="lastName" placeholder="Last Name" aria-label="Email" type="text" class="form-control">
            </div>
            <div class="col-3">
                <input id="email" name="email" placeholder="Email" aria-label="Email" type="email" class="form-control">
            </div>
            <div class="col-1">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>

    </form>
</x-layout>