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
        $routePattern = RoutePattern::select('id', 'name', 'code')->where('code', $row['route_pattern_code'])->first();

        if (! $routePattern) {
            return null; // skip invalid
        }

        return RouteDirection::updateOrCreate(
            [
                'route_pattern_id' => $routePattern->id,
                'name'             => $row['name'] ?? '',
                'direction'        => $row['direction'],
            ],
            []
        );
    }
}
