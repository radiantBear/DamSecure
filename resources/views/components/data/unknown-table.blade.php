<div>
    <div class="row">
        <div class="col">
            <h2 class="h3">Unknown-Format Data</h2>
        </div>
    </div>
    <table class="table table-responsive table-striped">
        <thead>
            <tr>
                <th>Data</th>
                <th>Creation Timestamp</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($unknownData as $d)
            <tr>
                <td>{{ $d->data }}</td>
                <td>{{ $d->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
