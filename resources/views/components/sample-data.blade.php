<div class="row">
    <div class="col-sm">
        <x-data.json-table
            :json="[
                'fields' => ['id', 'location', 'temperature'],
                'data' => [
                    [
                        'data' => [
                            'id' => 5,
                            'location' => 'entrance',
                            'temperature' => 68.4
                        ],
                        'raw' => (object) [ 'created_at' => '2025-12-09 04:26:13' ]
                    ],
                    [
                        'data' => [
                            'id' => 2,
                            'location' => 'kitchen',
                            'temperature' => 74.2
                        ],
                        'raw' => (object) [ 'created_at' => '2025-12-09 04:26:40' ]
                    ],
                    [
                        'data' => [
                            'id' => 6,
                            'location' => 'entrance',
                            'temperature' => 68.2
                        ],
                        'raw' => (object) [ 'created_at' => '2025-12-09 04:28:20' ]
                    ],
                    [
                        'data' => [
                            'id' => 3,
                            'location' => 'kitchen',
                            'temperature' => 74.1
                        ],
                        'raw' => (object) [ 'created_at' => '2025-12-09 04:28:31' ]
                    ]
                ]
            ]"
        />
        Data uploaded with a <code>Content-Type: application/json</code> header will be
        parsed as JSON and rejected if it is improperly formatted. When viewed, the
        top-level fields will be parsed to form table columns.
    </div>
    <div class="col-sm">
        <x-data.csv-table
            :csv="[
                'length' => 3,
                'data' => [
                    [
                        'data' => [ 5, 'entrance', 68.4 ],
                        'raw' => (object) [ 'created_at' => '2025-12-09 04:26:13' ]
                    ],
                    [
                        'data' => [ 2, 'kitchen', 74.2 ],
                        'raw' => (object) [ 'created_at' => '2025-12-09 04:26:40' ]
                    ],
                    [
                        'data' => [ 6, 'entrance', 68.2 ],
                        'raw' => (object) [ 'created_at' => '2025-12-09 04:28:20' ]
                    ],
                    [
                        'data' => [ 3, 'kitchen', 74.1 ],
                        'raw' => (object) [ 'created_at' => '2025-12-09 04:28:31' ]
                    ]
                ]
            ]"
        />
        Data uploaded with a <code>Content-Type: text/csv</code> header will be parsed as
        CSV data using the rules of PHP's <code>str_getcsv()</code> function. When viewed,
        the fields will be parsed to form table columns. Since CSV fields are unnamed,
        columns will always be filled left-to-right with corresponding fields' data.
    </div>
    <div class="col-sm">
        <x-data.unknown-table
            :unknown-data="[
                (object) [
                    'data' => '5 | entrance | 68.4',
                    'created_at' => '2025-12-09 04:26:13'
                ],
                (object) [
                    'data' => '2 | kitchen | 74.2',
                    'created_at' => '2025-12-09 04:26:40'
                ],
                (object) [
                    'data' => '6 | entrance | 68.2',
                    'created_at' => '2025-12-09 04:28:20'
                ],
                (object) [
                    'data' => '3 | kitchen | 74.1',
                    'created_at' => '2025-12-09 04:28:31'
                ]
            ]"
        />
        Data uploaded with a <code>Content-Type: text/plain</code> header (or no
        <code>Content-Type</code> header) will be left untouched. Upload timestamps will
        still be displayed along with the data.
    </div>
</div>
