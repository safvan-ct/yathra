<?php
namespace App\Services\Bus;

use App\Models\RouteDirection;
use App\Models\RouteDirectionStop;
use App\Models\Stop;
use App\Models\TripSchedule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TripService
{
    public function checkRouteAndCreate($data, $tripId = null)
    {
        return DB::transaction(function () use ($data, $tripId) {
            $checkTripExist = TripSchedule::where('bus_id', $data['bus_id'])
                ->where('departure_time', $data['departure_time'])
                ->when($tripId, fn($query) => $query->where('id', '!=', $tripId))
                ->exists();

            if ($checkTripExist) {
                return ['status' => false, 'message' => 'Trip already exist.'];
            }

            $exists = TripSchedule::where('bus_id', $data['bus_id'])
                ->where(function ($query) use ($data) {
                    $query->where('departure_time', '<=', $data['arrival_time'])->where('arrival_time', '>=', $data['departure_time']);
                })
                ->when($tripId, fn($query) => $query->where('id', '!=', $tripId))
                ->exists();

            if ($exists) {
                return ['status' => false, 'message' => 'Trip time already exist.'];
            }

            $checkRouteExist = RouteDirection::select('id')
                ->where('origin_stop_id', $data['origin_stop_id'])
                ->where('destination_stop_id', $data['destination_stop_id'])
                ->first();

            // If route does not exist, create a new route
            if (! $checkRouteExist) {
                $stops = Stop::select('id', 'name')
                    ->whereIn('id', [$data['origin_stop_id'], $data['destination_stop_id']])
                    ->get();

                $origin = $stops->where('id', $data['origin_stop_id'])->first();
                $dest   = $stops->where('id', $data['destination_stop_id'])->first();

                $save = [
                    'name'                => $origin->name . ' - ' . $dest->name,
                    'origin_stop_id'      => $origin->id,
                    'destination_stop_id' => $dest->id,
                ];

                if (isset($data['auth_status'])) {
                    $save['auth_status'] = $data['auth_status'];
                }

                $checkRouteExist = RouteDirection::create($save);

                // Create route stops
                $departure = Carbon::parse($data['departure_time']);
                $arrival   = Carbon::parse($data['arrival_time']);

                $minutes = $departure->diffInMinutes($arrival);

                $routeStops = [
                    [
                        'route_direction_id'         => $checkRouteExist->id,
                        'stop_id'                    => $data['origin_stop_id'],
                        'stop_order'                 => 1,
                        'minutes_from_previous_stop' => 0,
                        'default_offset_minutes'     => 0,
                    ],
                    [
                        'route_direction_id'         => $checkRouteExist->id,
                        'stop_id'                    => $data['destination_stop_id'],
                        'stop_order'                 => 2,
                        'minutes_from_previous_stop' => $minutes,
                        'default_offset_minutes'     => $minutes,
                    ],
                ];

                RouteDirectionStop::insert($routeStops);
            }

            // Create trip
            $save = [
                'route_direction_id'     => $checkRouteExist->id,
                'bus_id'                 => $data['bus_id'],
                'departure_time'         => $data['departure_time'],
                'arrival_time'           => $data['arrival_time'],
                'days_of_week'           => $data['days_of_week'],
                'time_between_stops_sec' => $data['time_between_stops_sec'],
            ];

            if (isset($data['auth_status'])) {
                $save['auth_status'] = $data['auth_status'];
            }

            if (isset($data['status'])) {
                $save['is_active'] = $data['status'];
            }

            if ($tripId) {
                $trip = TripSchedule::where('id', $tripId)->update($save);
            } else {
                $trip = TripSchedule::create($save);
            }

            return ['status' => true, 'trip' => $trip];
        });
    }

    public function findOrfail($id)
    {
        return TripSchedule::findOrFail($id);
    }

    public function getTripsByBus($busId)
    {
        return TripSchedule::with(['routeDirection', 'bus'])->where('bus_id', $busId)->orderBy('departure_time')->get();
    }
}
