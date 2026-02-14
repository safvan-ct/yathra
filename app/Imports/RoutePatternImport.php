<?php
namespace App\Imports;

use App\Models\RoutePattern;
use App\Models\Stop;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RoutePatternImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $origin      = Stop::select('id', 'code')->where('code', $row['origin_code'])->first();
        $destination = Stop::select('id', 'code')->where('code', $row['destination_code'])->first();

        if (! $origin || ! $destination || $origin->id === $destination->id) {
            return null; // skip invalid
        }

        return RoutePattern::updateOrCreate(
            [
                'origin_stop_id'      => $origin->id,
                'destination_stop_id' => $destination->id,
            ],
            [
                'name' => $row['name'],
                'code' => $origin->code . '-' . $destination->code,
            ]
        );
    }
}
