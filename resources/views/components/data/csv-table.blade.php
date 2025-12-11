<div>
    <div class="row">
        <div class="col">
            <h2 class="h3">CSV Data</h2>
        </div>
    </div>
    <table class="table table-responsive table-striped">
        <thead>
            <tr>
                @for ($i = 0; $i < $csv['length']; $i++)
                <th></th>
                @endfor
                <th>Creation Timestamp</th>
                <th></th>
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
                        <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                    </form>
                    @endcan
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
