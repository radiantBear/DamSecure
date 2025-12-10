<?php

namespace Tests\Feature\Components;

use App\Models;
use App\View\Components\Data;
use Tests\TestCase;

class DataTest extends TestCase
{
    public function test_json_table_lines_up_fields(): void
    {
        $json = [
            'fields' => ['id', 'name', 'temp', 'new', 'object'],
            'data' => [
                [
                    'raw' => Models\Data::factory()->create(['type' => 'json', 'data' => '{"id": 1, "name": "John Doe", "temp": 68}']),
                    'data' => ['id' => 1, 'name' => 'John Doe', 'temp' => 68]
                ],
                [
                    'raw' => Models\Data::factory()->create(['type' => 'json', 'data' => '{"id": 2, "name": "John Doe", "temp": 70, "new": null}']),
                    'data' => ['id' => 2, 'name' => 'John Doe', 'temp' => 70, 'new' => null]
                ],
                [
                    'raw' => Models\Data::factory()->create(['type' => 'json', 'data' => '{"id": 3, "name": "John Doe", "temp": 71, "new": true}']),
                    'data' => ['id' => 3, 'name' => 'John Doe', 'temp' => 71, 'new' => true]
                ],
                [
                    'raw' => Models\Data::factory()->create(['type' => 'json', 'data' => '{"id": 4, "temp": 70, "new": [1,2,3], "object": {"key": "value"}']),
                    'data' => ['id' => 4, 'temp' => 70, 'new' => [1, 2, 3], 'object' => ['key' => 'value']]
                ],
            ]
        ];

        $view = $this->component(Data\JsonTable::class, [ 'json' => $json ]);

        $view->assertSeeText('JSON Data');
        $view->assertSeeInOrder(['<th>id</th>', '<th>name</th>', '<th>temp</th>', '<th>new</th>', '<th>object</th>'], false);
        $view->assertSeeInOrder([
            '<td>1</td>', '<td>John Doe</td>', '<td>68</td>', '<td></td>', '<td></td>',
            '<td>2</td>', '<td>John Doe</td>', '<td>70</td>', '<td></td>', '<td></td>',
            '<td>3</td>', '<td>John Doe</td>', '<td>71</td>', '<td>true</td>', '<td></td>',
            '<td>4</td>', '<td></td>',         '<td>70</td>', '<td>[1,2,3]</td>', '<td>{&quot;key&quot;:&quot;value&quot;}</td>',
        ], false);
    }


    public function test_json_visible_when_populated(): void
    {
        $json = [
            'fields' => ['id', 'name', 'temp', 'new'],
            'data' => [
                [ 'data' => ['id' => 1, 'name' => 'John Doe', 'temp' => 68], 'created_at' => '2025-12-10' ]
            ]
        ];

        $table = new Data\JsonTable($json);

        $this->assertEquals($table->shouldRender(), true);
    }


    public function test_json_hidden_when_empty(): void
    {
        $json = [
            'fields' => [],
            'data' => []
        ];

        $table = new Data\JsonTable($json);

        $this->assertEquals($table->shouldRender(), false);
    }


    public function test_csv_table_fills_columns_left_to_right(): void
    {
        $csv = [
            'length' => 4,
            'data' => [
                [
                    'raw' => Models\Data::factory()->create(['type' => 'csv', 'data' => '1,70,unknown']),
                    'data' => [1, 70, 'unknown']
                ],
                [
                    'raw' => Models\Data::factory()->create(['type' => 'csv', 'data' => '2,other,68,true']),
                    'data' => [2, 'other', 68, 'true']
                ],
                [
                    'raw' => Models\Data::factory()->create(['type' => 'csv', 'data' => '3,71,unknown']),
                    'data' => [3, 71, 'unknown']
                ]
            ]
        ];

        $view = $this->component(Data\CsvTable::class, [ 'csv' => $csv ]);

        $view->assertSeeText('CSV Data');
        $view->assertSeeInOrder(['<th></th>', '<th></th>', '<th></th>', '<th></th>'], false);
        $view->assertSeeInOrder([
            '<td>1</td>', '<td>70</td>',    '<td>unknown</td>', '<td></td>',
            '<td>2</td>', '<td>other</td>', '<td>68</td>',      '<td>true</td>',
            '<td>3</td>', '<td>71</td>',    '<td>unknown</td>', '<td></td>',
        ], false);
    }


    public function test_csv_visible_when_populated(): void
    {
        $csv = [
            'length' => 0,
            'data' => [
                ['data' => [1, 70, 'unknown'], 'created_at' => '2025-11-20']
            ]
        ];

        $table = new Data\CsvTable($csv);

        $this->assertEquals($table->shouldRender(), true);
    }


    public function test_csv_hidden_when_empty(): void
    {
        $csv = [
            'length' => 0,
            'data' => []
        ];

        $table = new Data\CsvTable($csv);

        $this->assertEquals($table->shouldRender(), false);
    }


    public function test_unknown_table_shows_raw_text(): void
    {
        $data = [
            (object)['data' => "[1, 70, 'unknown']", 'created_at' => '2025-11-20'],
            (object)['data' => '{"data": true}', 'created_at' => '2025-11-21'],
            (object)['data' => '1,2,3', 'created_at' => '2025-11-22']
        ];

        $view = $this->component(Data\UnknownTable::class, [ 'unknownData' => $data ]);

        $view->assertSeeText('Unknown-Format Data');
        $view->assertSee('<th>Data</th>', false);
        $view->assertSeeInOrder([
            '<td style="white-space: pre-wrap;">[1, 70, &#039;unknown&#039;]</td>',
            '<td style="white-space: pre-wrap;">{&quot;data&quot;: true}</td>',
            '<td style="white-space: pre-wrap;">1,2,3</td>'
        ], false);
    }


    public function test_unknown_visible_when_populated(): void
    {
        $unknown = [
            (object)['data' => "[1, 70, 'unknown']", 'created_at' => '2025-11-20']
        ];

        $table = new Data\UnknownTable($unknown);

        $this->assertEquals($table->shouldRender(), true);
    }


    public function test_unknown_hidden_when_empty(): void
    {
        $unknown = [];

        $table = new Data\UnknownTable($unknown);

        $this->assertEquals($table->shouldRender(), false);
    }
}
