<div>
    <div class="row">
        <div class="col">
            <h3>CSV Data</h3>
        </div>
    </div>
    <table class="table table-responsive table-striped">
        <thead>
            <tr>
                @for ($i = 0; $i < $csv['length']; $i++)
                <th></th>
                @endfor
                <th>Creation Timestamp (UTC)</th>
                <th class="text-end">
                    @if (isset($projectUuid))
                    <form method="get" action="projects/{{ $projectUuid }}/data">
                        <input type="hidden" name="type" value="csv">
                        <button type="submit" class="btn btn-outline-primary"><i class="fa-solid fa-file-arrow-down"></i> Download</button>
                    </form>
                    @endif
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($csv['data'] as $d)
            <tr>
                @for ($i = 0; $i < $csv['length']; $i++)
                <td>{{ count($d['data']) > $i ? $d['data'][$i] : '' }}</td>
                @endfor
                <td>{{ $d['raw']->created_at }}</td>
                <td class="text-end">
                    @can('delete', $d['raw'])
                    <form method="post" action="data/{{ $d['raw']->id }}">
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
