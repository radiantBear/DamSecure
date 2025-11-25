<?php

namespace Tests\Unit;

use App\Services\DataService;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\TestCase;

class DataServiceTest extends TestCase
{
    public function test_splitting_data_works(): void
    {
        $collection = new Collection([
            (object)[ 'type' => 'json', 'data' => '{"a":1}', 'created_at' => '2024-01-01' ],
            (object)[ 'type' => 'csv', 'data' => "1,2,3", 'created_at' => '2024-01-02' ],
            (object)[ 'type' => 'json', 'data' => '{"b":2}', 'created_at' => '2024-01-03' ],
            (object)[ 'type' => 'unknown', 'data' => '???', 'created_at' => '2024-01-06' ],
            (object)[ 'type' => 'other', 'data' => 'just some text', 'created_at' => '2024-01-07' ],
            (object)[ 'type' => 'csv', 'data' => "4,5,6", 'created_at' => '2024-01-09' ],
        ]);

        $result = DataService::splitData($collection);

        $this->assertCount(2, $result['json']);
        $this->assertCount(2, $result['csv']);
        $this->assertCount(2, $result['unknown']);

        $this->assertEquals(['a' => 1], $result['json'][0]['data']);
        $this->assertEquals(['b' => 2], $result['json'][1]['data']);
        $this->assertEquals(['1', '2', '3'], $result['csv'][0]['data']);
        $this->assertEquals(['4', '5', '6'], $result['csv'][1]['data']);
        $this->assertEquals('???', $result['unknown'][0]->data);
        $this->assertEquals('just some text', $result['unknown'][1]->data);
    }


    public function test_merging_json_fields_includes_all_unique_keys()
    {
        $data = [
            ['data' => ['a' => 1, 'b' => 2]],
            ['data' => ['a' => 3, 'c' => 4]],
            ['data' => ['d' => 9, 'b' => 4]],
        ];

        $result = DataService::getJsonFields($data);

        $this->assertContains('a', $result);
        $this->assertContains('b', $result);
        $this->assertContains('c', $result);
        $this->assertContains('d', $result);
    }


    public function test_merging_json_fields_merges_all_duplicate_keys()
    {
        $data = [
            ['data' => ['a' => 1, 'b' => 2]],
            ['data' => ['a' => 3, 'b' => 4]],
            ['data' => ['a' => 9, 'b' => 4]]
        ];

        $result = DataService::getJsonFields($data);

        $this->assertEqualsCanonicalizing(['a', 'b'], $result);
    }


    public function test_getting_csv_length_finds_max_csv_fields_needed()
    {
        $data = [
            ['data' => ['a', 'b', 'c']],
            ['data' => ['d', 'e']],
            ['data' => ['f', 'g', 'h', 'i']],
            ['data' => ['j']]
        ];

        $result = DataService::getCsvLength($data);

        $this->assertEquals(4, $result);
    }


    public function test_getting_csv_length_finds_max_csv_fields_needed_if_first()
    {
        $data = [
            ['data' => ['f', 'g', 'h', 'i']],
            ['data' => ['a', 'b', 'c']],
            ['data' => ['d', 'e']],
            ['data' => ['j']]
        ];

        $result = DataService::getCsvLength($data);

        $this->assertEquals(4, $result);
    }


    public function test_getting_csv_length_finds_max_csv_fields_needed_if_last()
    {
        $data = [
            ['data' => ['a', 'b', 'c']],
            ['data' => ['d', 'e']],
            ['data' => ['j']],
            ['data' => ['f', 'g', 'h', 'i']]
        ];

        $result = DataService::getCsvLength($data);

        $this->assertEquals(4, $result);
    }
}
