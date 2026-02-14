<?php
namespace App\Imports;

use App\Models\RouteDirection;
use App\Models\RouteDirectionStop;
use App\Models\Stop;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RouteDirectionStopPreviewImport implements ToCollection, WithHeadingRow
{
    public array $preview = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            $valid   = true;
            $message = '';

            $routeDirection = RouteDirection::select('id')->where('id', $row['route_direction_id'])->first();

            if (! $routeDirection) {
                $message .= 'Route direction not found,';
                $valid    = false;
            }

            $stop = Stop::select('id', 'name', 'code')->where('code', $row['stop_code'])->first();

            if (! $stop) {
                $message .= 'Stop not found,';
                $valid    = false;
            }

            $duplicate = RouteDirectionStop::where('route_direction_id', $routeDirection?->id)
                ->where('stop_id', $stop?->id)
                ->exists();

            if ($duplicate) {
                $message .= 'Duplicate route direction found,';
                $valid    = false;
            }

            $this->preview[] = [
                'stop'                       => $stop?->name . ' (' . $stop?->code . ')',
                'stop_order'                 => $row['stop_order'],
                'minutes_from_previous_stop' => $row['minutes_from_previous_stop'],
                'valid'                      => $valid,
                'message'                    => $message,
            ];
        }
    }
}
