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
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($unknownData as $d)
            <tr>
                <td style="white-space: pre-wrap;">{{ $d->data }}</td>
                <td>{{ $d->created_at }}</td>
                <td class="text-end">
                    @can('delete', $d)
                    <form method="post" action="data/{{ $d->id }}">
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
</div>
