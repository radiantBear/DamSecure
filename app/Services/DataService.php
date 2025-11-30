<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;

class DataService
{
    public static function splitData(Collection $data)
    {
        $jsonData = [];
        $csvData = [];
        $unknownData = [];

        foreach ($data as $d) {
            if ($d->type === 'json') {
                $jsonData[] = [ 'data' => json_decode($d->data, true), 'created_at' => $d->created_at ];
            } elseif ($d->type === 'csv') {
                $csvData[] = [ 'data' => str_getcsv($d->data), 'created_at' => $d->created_at ];
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
