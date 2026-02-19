<?php
namespace App\Imports;

use App\Models\RouteDirection;
use App\Models\RoutePattern;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RouteDirectionImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $routePattern = RoutePattern::select('id', 'name', 'code', 'origin_stop_id', 'destination_stop_id')
            ->with(['origin', 'destination'])
            ->where('code', $row['route_pattern_code'])
            ->first();

        if (! $routePattern) {
            return null; // skip invalid
        }

        $name = $row['direction'] == 'up' ? $routePattern->origin->name . ' - ' . $routePattern->destination->name : $routePattern->destination->name . ' - ' . $routePattern->origin->name;

        $origin = $row['direction'] == 'up' ? $routePattern->origin_stop_id : $routePattern->destination_stop_id;
        $dest   = $row['direction'] == 'up' ? $routePattern->destination_stop_id : $routePattern->origin_stop_id;

        return RouteDirection::updateOrCreate(
            [
                'route_pattern_id'    => $routePattern->id,
                'name'                => $name,
                'direction'           => $row['direction'],
                'origin_stop_id'      => $origin,
                'destination_stop_id' => $dest,
            ],
            []
        );
    }
}
