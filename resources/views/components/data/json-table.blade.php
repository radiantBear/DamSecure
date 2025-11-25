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
                                : $d['data'][$f]
                        ) : ''
                }}</td>
                @endforeach
                <td>{{ $d['created_at'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
