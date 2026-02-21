<?php
namespace App\Imports;

use App\Enums\AuthStatus;
use App\Models\Bus;
use App\Models\TripSchedule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TripSchedulesImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $buses = Bus::pluck('id', 'bus_number')->toArray();

        DB::transaction(function () use ($rows, $buses) {

            foreach ($rows as $row) {
                if (! $row['route_direction_id'] || ! $row['bus_number'] || ! $row['departure_time'] || ! $row['days_of_week'] || ! $row['arrival_time']) {
                    continue;
                }

                if (! isset($buses[$row['bus_number']])) {
                    continue;
                }
                $busId = $buses[$row['bus_number']];

                TripSchedule::updateOrCreate([
                    'route_direction_id' => $row['route_direction_id'], 'bus_id' => $busId, 'departure_time' => $row['departure_time'],
                ], [
                    'route_direction_id'     => $row['route_direction_id'],
                    'bus_id'                 => $busId,
                    'departure_time'         => $row['departure_time'],
                    'days_of_week'           => explode('|', $row['days_of_week']),
                    'arrival_time'           => $row['arrival_time'],
                    'time_between_stops_sec' => 75,
                    'auth_status'            => AuthStatus::APPROVED->value,
                ]);
            }
        });
    }
}
