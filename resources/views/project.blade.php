<x-layout>
    <x-slot:title>{{ $project->name }}</x-slot:title>

    <div class="row">
        <div class="col-lg-4"><!-- Offset for the button on the other end --></div>
        <div class="col text-center"><h1>{{ $project->name }}</h1></div>
        <div class="col-lg-4 d-flex gap-2 justify-content-end align-items-center">
            <a href="projects/{{ $project->uuid }}/permissions" class="btn btn-primary">
                <i class="fa-solid fa-users"></i> Permissions
            </a>
            @can('update', $project)
            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rotateTokenModal">
                <i class="fa-solid fa-key"></i> Rotate API token
            </button>
            @endcan
        </div>
    </div>

    <div class="d-flex flex-column gap-4">
        <x-data.json-table :$json />

        <x-data.csv-table :$csv />

        <x-data.unknown-table :unknown-data=$unknown />
    </div>

    <div class="modal fade" id="rotateTokenModal" tabindex="-1" aria-labelledby="rotateTokenModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="rotateTokenModalLabel">Rotate API token</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
                </div>
                <form action="projects/{{ $project->uuid }}/token">
                    <div class="modal-body">
                        <p>
                            Are you sure you wish to rotate your API token? All new data 
                            uploads with the old token will be rejected. Any devices you
                            have configured with the old token will need to be updated
                            with the new token before they can resume uploading data.
                        </p>
                        <label for="expiration" class="form-label">
                            New token expires in
                        </label>
                        <select id="expiration" name="expiration" class="form-select">
                            <option value="day">1 Day</option>
                            <option value="week">1 Week</option>
                            <option value="month">1 Month</option>
                            <option value="year" selected>1 Year</option>
                            <option value="never">Never</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">
                            Confirm
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if (session('apiToken'))
    <div class="modal fade" id="newTokenModal" tabindex="-1" aria-labelledby="newTokenModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="newTokenModalLabel">New API token</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
                </div>
                <div class="modal-body">
                    <p>
                        Your API token for uploading data is 
                        <code>{{ session('apiToken') }}</code>. 
                        @if (session('tokenExpiration'))
                        It is valid until <b>{{ session('tokenExpiration')->format('Y-m-d') }}</b>.
                        @else
                        It is valid indefinitely (no expiration date).
                        @endif
                        Be sure to save this token; you won't be able to view it again.
                    </p>
                    <p>
                        Any previous tokens for this project have been invalidated and can
                        no longer be used.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const myModal = new bootstrap.Modal(document.getElementById('newTokenModal'));
            myModal.show();
        });
    </script>
    @endif
</x-layout>
