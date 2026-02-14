<?php
namespace App\Imports;

use App\Models\RouteDirection;
use App\Models\RoutePattern;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RouteDirectionPreviewImport implements ToCollection, WithHeadingRow
{
    public array $preview = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            $routePattern = RoutePattern::select('id', 'name', 'code')->where('code', $row['route_pattern_code'])->first();

            $valid   = true;
            $message = '';

            if (! $routePattern) {
                $message .= 'Route pattern not found,';
                $valid    = false;
            }

            $duplicate = RouteDirection::where('route_pattern_id', $routePattern?->id)
                ->where('name', $row['name'])
                ->where('direction', $row['direction'])
                ->exists();

            if ($duplicate) {
                $message .= 'Duplicate route direction found,';
                $valid    = false;
            }

            $this->preview[] = [
                'name'      => $row['name'],
                'direction' => $row['direction'],
                'route'     => "{$routePattern?->name} ({$routePattern?->code})",
                'valid'   => $valid,
                'message' => $message,
            ];
        }
    }
}
