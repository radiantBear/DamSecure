<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;

// NOTE: if there are changes to how data is parsed, be sure to update the documentation
// on the homepage

class DataService
{
    public static function splitData(Collection $data)
    {
        $jsonData = [];
        $csvData = [];
        $unknownData = [];

        foreach ($data as $d) {
            if ($d->type === 'json') {
                $jsonData[] = [ 'raw' => $d, 'data' => json_decode($d->data, true) ];
            } elseif ($d->type === 'csv') {
                $csvData[] = [ 'raw' => $d, 'data' => str_getcsv($d->data) ];
            } else {
                $unknownData[] = $d;
            }
        }

        return [
            'json' => $jsonData,
            'csv' => $csvData,
            'unknown' => $unknownData
        ];
    }


    public static function getJsonFields(array $json): array
    {
        return array_keys(
            array_reduce(
                array_map(fn ($d) => $d['data'], $json),
                function ($carry, $d) {
                    foreach ($d as $key => $_) {
                        $carry[$key] = true;
                    }
                    return $carry;
                },
                []
            )
        );
    }


    public static function getCsvLength(array $csv): int
    {
        return count($csv) > 0
            ? max(array_map(
                fn ($c) => count($c['data']),
                $csv
            ))
            : 0;
    }
}
