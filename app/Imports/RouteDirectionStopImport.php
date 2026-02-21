<?php
namespace App\Imports;

use App\Models\RouteDirection;
use App\Models\RouteDirectionStop;
use App\Models\Stop;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RouteDirectionStopImport implements ToModel, WithHeadingRow
{
    protected int $offset           = 0;
    protected bool $isFirstRow      = true;
    protected int $routeDirectionId = 0;

    public function model(array $row)
    {
        $routeDirection = RouteDirection::select('id')->where('id', $row['route_direction_id'])->first();
        $stop           = Stop::select('id', 'name', 'code')->where('code', $row['stop_code'])->first();

        if (! $routeDirection || ! $stop) {
            return null; // skip invalid
        }

        if ($this->routeDirectionId == 0 || $routeDirection->id != $this->routeDirectionId) {
            $this->offset = 0;
        }

        // First row → offset = 0
        if ($this->isFirstRow) {
            $currentOffset    = 0;
            $minutes          = 0;
            $this->isFirstRow = false;
        } else {
            $minutes        = (int) ($row['minutes_from_previous_stop'] ?? 0);
            $this->offset  += $minutes;
            $currentOffset  = $this->offset;
        }

        $this->routeDirectionId = $routeDirection->id;

        return RouteDirectionStop::updateOrCreate(['route_direction_id' => $routeDirection->id, 'stop_id' => $stop->id], [
            'stop_order'                 => $row['stop_order'],
            'minutes_from_previous_stop' => $minutes,
            'default_offset_minutes'     => $currentOffset,
        ]);
    }
}
