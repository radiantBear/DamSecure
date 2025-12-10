<x-layout>
    <x-slot:title>{{ $project->name }} Permissions</x-slot:title>

    <div class="row">
        <div class="col text-center"><h1>{{ $project->name }} Permissions</h1></div>
    </div>

    <div class="row">
        <div class="col"><h2>Tokens</h2></div>
    </div>

    <table class="table table-responsive table-striped">
        <thead>
            <tr>
                <th>Token Type</th>
                <th>Last Used</th>
                <th>Expiration Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Upload (modify data)</td>
                <td>
                    @if (! is_null($uploadToken))
                    {{ $uploadToken->last_used_at ?? 'Never' }}
                    @endif
                </td>
                <td>
                    @if (! is_null($uploadToken))
                    {{ $uploadToken->expires_at ?? 'Never' }}
                    @else
                    Token does not exist
                    @endif
                </td>
                <td class="text-end">
                    @can('update', $project)
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rotateTokenModal" data-bs-scope="upload">
                            <i class="fa-solid fa-rotate"></i>
                            Rotate
                        </button>
                    </form>
                    @endcan
                </td>
            </tr>
            <tr>
                <td>Download (read data)</td>
                <td>
                    @if (! is_null($downloadToken))
                    {{ $downloadToken->last_used_at ?? 'Never' }}
                    @endif
                </td>
                <td>
                    @if (! is_null($downloadToken))
                    {{ $downloadToken->expires_at ?? 'Never' }}
                    @else
                    Token does not exist
                    @endif
                </td>
                <td class="text-end">
                    @can('update', $project)
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#rotateTokenModal" data-bs-scope="download">
                            <i class="fa-solid fa-rotate"></i>
                            Rotate
                        </button>
                    </form>
                    @endcan
                </td>
            </tr>
        </tbody>
    </table>

    <div class="row">
        <div class="col"><h2>Users</h2></div>
    </div>

    <table class="table table-responsive table-striped">
        <thead>
            <tr>
                <th>ONID</th>
                <th>Permissions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($permissions as $p)
            <tr>
                <td>{{ $p->user->onid }}</td>
                <td class="d-flex gap-4">
                    <form method="post" action="permissions/{{ $p->id }}" class="flex-grow-1">
                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}
                        <select name="role" onchange="this.form.submit()" class="form-select" @cannot('update', $p) disabled @endcan>
                            <option value="owner" {{ $p->role === 'owner' ? 'selected' : '' }}>Owner</option>
                            <option value="contributor" {{ $p->role === 'contributor' ? 'selected' : '' }}>Contributor</option>
                            <option value="viewer" {{ $p->role === 'viewer' ? 'selected' : '' }}>Viewer</option>
                        </select>
                    </form>
                    @can('delete', $p)
                    <form method="post" action="permissions/{{ $p->id }}">
                        {{ csrf_field() }}
                        {{ method_field('DELETE') }}
                        <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                    </form>
                    @endcan
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="row">
        <div class="col">
            <ul>
                <li><strong>Owner:</strong> has full permissions for the project</li>
                <li><strong>Contributors:</strong> can view project data and rotate the API key</li>
                <li><strong>Viewers:</strong> can view project data (including permissions)</li>
            </ul>
        </div>
    </div>

    @can('create', [App\Models\ProjectUser::class, $project])
    <form method="post" class="row needs-validation {{ $errors->isNotEmpty() ? 'was-validated' : '' }}" novalidate>
        {{ csrf_field() }}
        <div class="col">
            <input name="onid" type="text" placeholder="ONID" class="form-control" required>
            @error('onid')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col">
            <select name="role" class="form-select">
                <option value="contributor">Contributor</option>
                <option value="viewer">Viewer</option>
            </select>
        </div>
        <div class="col">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-user-plus"></i>
                Add
            </button>
        </div>
    </form>
    @endcan

    <x-api-token-display />

    <div class="modal fade" id="rotateTokenModal" tabindex="-1" aria-labelledby="rotateTokenModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="rotateTokenModalLabel">Rotate <span class="token-scope-display"></span> API token</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="close"></button>
                </div>
                <form id="tokenForm" method="post">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }}
                    <div class="modal-body">
                        <p>
                            Are you sure you wish to rotate your API token? All new data 
                            <span class="token-scope-display"></span> with the old token will be
                            rejected. Any devices you have configured with the old token
                            will need to be updated with the new token before they can
                            resume operation.
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
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const rotateTokenModal = document.getElementById('rotateTokenModal')
            rotateTokenModal.addEventListener('show.bs.modal', e => {
                // Button that triggered the modal
                const button = e.relatedTarget;

                const scope = button.getAttribute('data-bs-scope');

                // Update the modal's content
                const tokenScopeDisplays = rotateTokenModal.getElementsByClassName('token-scope-display');
                const tokenForm = document.getElementById('tokenForm');

                for (const e of tokenScopeDisplays) {
                    e.textContent = scope;
                }
                tokenForm.action = `projects/{{ $project->uuid }}/tokens/${scope}`;
            });
        });
    </script>
</x-layout>
