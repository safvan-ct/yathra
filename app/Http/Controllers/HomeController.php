<?php
namespace App\Http\Controllers;

use App\Models\Stop;
use App\Models\TripSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // $fromStopId = $request->filled('from_stop_id') ? $request->from_stop_id : 1;
        // $toStopId   = $request->filled('to_stop_id') ? $request->to_stop_id : 28;

        // $buses = DB::table('trips as t')

        // // Join bus + operator
        //     ->join('buses as b', 'b.id', '=', 't.bus_id')
        //     ->join('operators as o', 'o.id', '=', 'b.operator_id')

        // // FROM stop
        //     ->join('route_pattern_stops as rps_from', function ($join) use ($fromStopId) {
        //         $join->on('rps_from.route_pattern_id', '=', 't.route_pattern_id')
        //             ->where('rps_from.stop_id', '=', $fromStopId);
        //     })

        // // TO stop
        //     ->join('route_pattern_stops as rps_to', function ($join) use ($toStopId) {
        //         $join->on('rps_to.route_pattern_id', '=', 't.route_pattern_id')
        //             ->where('rps_to.stop_id', '=', $toStopId);
        //     })

        // // Final stop validation
        //     ->join('route_pattern_stops as rps_final', function ($join) {
        //         $join->on('rps_final.route_pattern_id', '=', 't.route_pattern_id')
        //             ->on('rps_final.stop_id', '=', 't.final_stop_id');
        //     })

        // // Skip checks
        //     ->leftJoin('trip_stop_overrides as tso_from', function ($join) use ($fromStopId) {
        //         $join->on('tso_from.trip_id', '=', 't.id')
        //             ->where('tso_from.stop_id', '=', $fromStopId);
        //     })

        //     ->leftJoin('trip_stop_overrides as tso_to', function ($join) use ($toStopId) {
        //         $join->on('tso_to.trip_id', '=', 't.id')
        //             ->where('tso_to.stop_id', '=', $toStopId);
        //     })

        // // Direction check
        //     ->whereColumn('rps_from.stop_order', '<', 'rps_to.stop_order')

        // // Must reach destination
        //     ->whereColumn('rps_final.stop_order', '>=', 'rps_to.stop_order')

        // // Skip filtering
        //     ->where(function ($q) {
        //         $q->whereNull('tso_from.is_skipped')
        //             ->orWhere('tso_from.is_skipped', false);
        //     })
        //     ->where(function ($q) {
        //         $q->whereNull('tso_to.is_skipped')
        //             ->orWhere('tso_to.is_skipped', false);
        //     })

        //     ->where('t.is_active', true)

        //     ->select([
        //         't.id as trip_id',
        //         'b.bus_number',
        //         'b.bus_name',
        //         'o.name as operator',
        //         't.service_type',

        //         DB::raw('ADDTIME(t.start_time, SEC_TO_TIME(COALESCE(tso_from.custom_offset_minutes, rps_from.default_offset_minutes) * 60)) as departure_time'),

        //         DB::raw('ADDTIME(t.start_time, SEC_TO_TIME(COALESCE(tso_to.custom_offset_minutes, rps_to.default_offset_minutes) * 60)) as arrival_time'),
        //     ])

        // // ORDER BY ETA at FROM stop
        //     ->orderBy('departure_time')
        //     ->get()
        //     ->toArray();

        $fromStopId = $request->filled('from_stop_id') ? $request->from_stop_id : 1;
        $toStopId   = $request->filled('to_stop_id') ? $request->to_stop_id : 28;

        $buses = DB::table('trip_schedules as ts')

        // Join bus + operator
            ->join('buses as b', 'b.id', '=', 'ts.bus_id')

        // FROM stop
            ->join('route_direction_stops as rds_from', function ($join) use ($fromStopId) {
                $join->on('rds_from.route_direction_id', '=', 'ts.route_direction_id')
                    ->where('rds_from.stop_id', '=', $fromStopId);
            })

        // TO stop
            ->join('route_direction_stops as rds_to', function ($join) use ($toStopId) {
                $join->on('rds_to.route_direction_id', '=', 'ts.route_direction_id')
                    ->where('rds_to.stop_id', '=', $toStopId);
            })

        // Ensure correct travel direction
            ->whereColumn('rds_from.stop_order', '<', 'rds_to.stop_order')

        // Active schedule only
            ->where('ts.is_active', true)

            ->select([
                'ts.id as trip_id',
                'b.bus_name',
                'b.bus_number',
                'ts.departure_time',

                DB::raw("ADDTIME(ts.departure_time, SEC_TO_TIME(rds_from.default_offset_minutes * 60) ) as departure_time"),

                DB::raw("ADDTIME(ts.departure_time, SEC_TO_TIME(rds_to.default_offset_minutes * 60)) as arrival_time"),
            ])

            ->orderBy('departure_time')
            ->get()
            ->toArray();

        $fromStop = request('from_stop_id')
            ? Stop::select('id', 'name', 'code')->find(request('from_stop_id'))
            : null;

        $toStop = request('to_stop_id')
            ? Stop::select('id', 'name', 'code')->find(request('to_stop_id'))
            : null;

        return view('home', compact('buses', 'fromStop', 'toStop'));
    }

    public function showTrip($tripId, $from_stop_id, $to_stop_id)
    {
        // $trip = Trip::findOrFail($tripId);

        // $fromStopId = $from_stop_id;
        // $toStopId   = $to_stop_id;

        // $stops = DB::table('route_pattern_stops as rps')
        //     ->join('stops as s', 's.id', '=', 'rps.stop_id')

        //     ->leftJoin('trip_stop_overrides as tso', function ($join) use ($tripId) {
        //         $join->on('tso.stop_id', '=', 'rps.stop_id')
        //             ->where('tso.trip_id', '=', $tripId);
        //     })

        //     ->where('rps.route_pattern_id', $trip->route_pattern_id)
        //     ->whereNull('tso.is_skipped')
        //     ->orderBy('rps.stop_order')

        //     ->select([
        //         's.id as stop_id',
        //         's.name',
        //         'rps.stop_order',
        //         DB::raw('COALESCE(tso.custom_offset_minutes, rps.default_offset_minutes) as offset'),
        //     ])
        //     ->get();

        // // Determine selected range
        // $fromOrder = $stops->firstWhere('stop_id', $fromStopId)?->stop_order;
        // $toOrder   = $stops->firstWhere('stop_id', $toStopId)?->stop_order;

        // $stops = $stops->map(function ($stop) use ($trip, $fromOrder, $toOrder, $fromStopId, $toStopId) {

        //     $time = \Carbon\Carbon::parse($trip->start_time)
        //         ->addMinutes($stop->offset);

        //     $stop->start_time     = \Carbon\Carbon::parse($trip->start_time)->format('h:i A');
        //     $stop->arrival_time   = $time->format('h:i A');
        //     $stop->departure_time = $time->copy()->addMinutes(1)->format('h:i A');

        //     $stop->is_in_between = (
        //         $fromOrder && $toOrder &&
        //         $stop->stop_order >= $fromOrder &&
        //         $stop->stop_order <= $toOrder
        //     );

        //     $stop->is_selected_segment = in_array($stop->stop_id, [$fromStopId, $toStopId]);

        //     return $stop;
        // });

        $trip = TripSchedule::findOrFail($tripId);

        $fromStopId = $from_stop_id;
        $toStopId   = $to_stop_id;

        $stops = DB::table('route_direction_stops as rds')
            ->join('stops as s', 's.id', '=', 'rds.stop_id')

            ->where('rds.route_direction_id', $trip->route_direction_id)
            ->orderBy('rds.stop_order')

            ->select([
                's.id as stop_id',
                's.name',
                's.code',
                'rds.stop_order',
                'rds.default_offset_minutes as offset',
            ])
            ->get();

        $fromOrder = $stops->firstWhere('stop_id', $fromStopId)?->stop_order;
        $toOrder   = $stops->firstWhere('stop_id', $toStopId)?->stop_order;

        $stops = $stops->map(function ($stop) use ($trip, $fromOrder, $toOrder, $fromStopId, $toStopId) {

            $startTime = \Carbon\Carbon::parse($trip->departure_time);

            $arrivalTime   = $startTime->copy()->addMinutes($stop->offset);
            $departureTime = $arrivalTime->copy()->addMinutes(1); // 1 min halt

            $stop->trip_start_time = $startTime->format('h:i A');
            $stop->arrival_time    = $arrivalTime->format('h:i A');
            $stop->departure_time  = $departureTime->format('h:i A');

            // Highlight selected segment
            $stop->is_in_between = (
                $fromOrder !== null &&
                $toOrder !== null &&
                $stop->stop_order >= $fromOrder &&
                $stop->stop_order <= $toOrder
            );

            $stop->is_selected_segment = in_array($stop->stop_id, [$fromStopId, $toStopId]);

            return $stop;
        });

        return view('bus-route', compact('trip', 'stops', 'fromStopId', 'toStopId'));
    }

}
