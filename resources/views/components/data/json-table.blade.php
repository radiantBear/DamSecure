<div>
    <div class="row">
        <div class="col">
            <h2 class="h3">JSON Data</h2>
        </div>
    </div>
    <table class="table table-responsive table-striped">
        <thead>
            <tr>
                @foreach ($json['fields'] as $f)
                <th>{{ $f }}</th>
                @endforeach
                <th>Creation Timestamp</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($json['data'] as $d)
            <tr>
                @foreach ($json['fields'] as $f)
                <td>{{
                    array_key_exists($f, $d['data']) 
                        ? (
                            is_bool($d['data'][$f])
                                ? ($d['data'][$f] ? 'true' : 'false')
                                : (
                                    is_object($d['data'][$f]) || is_array($d['data'][$f])
                                        ? json_encode($d['data'][$f]) 
                                        : $d['data'][$f]
                                )
                        ) : ''
                }}</td>
                @endforeach
                <td>{{ $d['raw']->created_at }}</td>
                <td>
                    @can('delete', $d['raw'])
                    <form method="post" action="data/{{ $d['id'] }}">
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
