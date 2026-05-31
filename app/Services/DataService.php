<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

// NOTE: if there are changes to how data is parsed, be sure to update the documentation
// on the homepage

class DataService
{
    public static function getDataType(Request $request)
    {
        if ($request->header('Content-Type') === 'application/json') {
            return 'json';
        }
        if ($request->header('Content-Type') === 'text/csv') {
            return 'csv';
        }

        return 'unknown';
    }

    public static function validateData($data, string $type)
    {
        // JSON should be valid and an object so there're keys for column names
        if (
            $type === 'json'
            && (!json_validate($data) || !is_object(json_decode($data)))
        ) {
            return [false, 'Invalid JSON'];
        }

        return [true, ''];
    }

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

    public static function jsonToTable(Collection $data): array
    {
        $decoded_data = [];
        foreach ($data as $d) {
            $decoded_data[] = ['data' => json_decode($d->data, true)];
        }

        $keys = self::getJsonFields($decoded_data);

        $normalized_data = [];
        foreach ($decoded_data as $d) {
            $tmp = [];
            foreach ($keys as $k) {
                if (isset($d['data'][$k])) {
                    $tmp[] = $d['data'][$k];
                } else {
                    $tmp[] = null;
                }
            }

            $normalized_data[] = $tmp;
        }

        return [
            $keys,
            ...$normalized_data
        ];
    }

    public static function csvToTable(Collection $data): array
    {
        $decoded_data = [];
        foreach ($data as $d) {
            $decoded_data[] = ['data' => str_getcsv($d->data)];
        }

        $length = self::getCsvLength($decoded_data);

        $normalized_data = [];
        foreach ($decoded_data as $d) {
            $normalized_data[] = $d['data'];
        }

        return [
            array_fill(0, $length, ""),
            ...$normalized_data
        ];
    }

    public static function unknownToTable(Collection $data): array
    {
        $decoded_data = [];
        foreach ($data as $d) {
            $decoded_data[] = [$d->data];
        }

        return [
            ['data'],
            ...$decoded_data
        ];
    }
}
