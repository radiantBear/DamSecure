<div>
    <div class="row">
        <div class="col">
            <h3>Unknown-Format Data</h3>
        </div>
    </div>
    <table class="table table-responsive table-striped">
        <thead>
            <tr>
                <th>Data</th>
                <th>Creation Timestamp (UTC)</th>
                <th class="text-end">
                    @if (isset($projectUuid))
                    <form method="get" action="projects/{{ $projectUuid }}/data">
                        <input type="hidden" name="type" value="unknown">
                        <button type="submit" class="btn btn-outline-primary"><i class="fa-solid fa-file-arrow-down"></i> Download</button>
                    </form>
                    @endif
                </th>
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
                        <button type="submit" class="btn btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                    </form>
                    @endcan
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
