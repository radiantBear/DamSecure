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
